<?php

namespace App\Http\Controllers;

// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use App\Mail\BookingCodeEmail;
use App\Mail\ExchangeConfirmation;
use App\Mail\ExchangeSuccessMail;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Exchange;
use App\Models\ExchangePolicy;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\RouteController;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    public function getPageExchange()
    {
        return view('pages.exchange');
    }

    public function getPageExchangeStep1()
    {
        return view('pages.exchange-selection');
    }

    public function getPageExchangeStep2($selectedTicketID)
    {
        return view('pages.exchange-verify', ['ticketID' => $selectedTicketID]);
    }

    public function findBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);

        $customer = Customer::where('email', $request->email)
            ->where('phone', $request->phone)
            ->first();

        if (!$customer) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Thông tin vé cần đổi không đúng, vui lòng kiểm tra lại']);
        }

        $booking = Booking::where('id', $request->booking_id)
            ->where('customer_id', $customer->id)
            ->first();

        if (!$booking) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Thông tin vé cần đổi không đúng, vui lòng kiểm tra lại.']);
        }

        $tickets = $booking->tickets;
        foreach ($tickets as $ticket) {
            $scheduleStart = $ticket->schedule->day_start . ' ' . $ticket->schedule->time_start;
            $hoursToDeparture = Carbon::now()->diffInHours(Carbon::parse($scheduleStart), false);
            $exchangePolicy = ExchangePolicy::where('min_hours', '<=', $hoursToDeparture)
                ->where('max_hours', '>=', $hoursToDeparture)
                ->first();
            if ($exchangePolicy) {
                $exchangeFee = $exchangePolicy->exchange_fee;
            } else {
                $exchangeFee = 1;
            }
            $ticket->exchange_fee = $exchangeFee;
        }

        return view('pages.exchange-selection', ['booking' => $booking, 'tickets' => $tickets]);
    }

    public function showBookingCodeForm()
    {
        return view('pages.refund-bookingCode');
    }

    public function sendBookingCode(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->email;

        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy khách hàng với email này.']);
        }

        $booking = Booking::where('customer_id', $customer->id)->first();;

        if (!$booking) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy mã đặt chỗ liên quan đến email này.']);
        }

        Mail::to($email)->send(new BookingCodeEmail($booking));

        return redirect()->back()->with('success', 'Mã đặt chỗ đã được gửi tới email của bạn.');
    }

    public function findTicket(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
        ]);

        $booking = Booking::find($request->booking_id);

        if (!$booking) {
            return redirect()->back()->withErrors(['booking_id' => 'Mã đặt vé không hợp lệ']);
        }

        $tickets = Ticket::where('booking_id', $booking->id)
            ->whereNull('exchange_id')
            ->whereNull('refund_id')
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()->back()
                ->with('warning', 'Không có vé nào có thể đổi cho mã đặt vé này.');
        }

        if ($request->has('ticket_array')) {
            $selectedTicket = Ticket::find($request->input('ticket_array'));

            return redirect()->route('exchange.search', ['selectedTicketId' => $selectedTicket->id])
                ->with('success', 'Vé đã được chọn. Tiến hành tìm vé để đổi.');
        }

        return redirect()->back()
            ->withErrors(['error' => 'Vui lòng chỉ chọn một vé để đổi']);
    }

    public function search($ticketId)
    {
        $ticket = Ticket::findOrFail($ticketId);

        $stationA = $ticket->schedule->station_start;
        $stationB = $ticket->schedule->station_end;
        $departureDate = $ticket->schedule->date_start;

        $routeController = new RouteController();
        $goRoutes = $routeController->findRoutes($stationA, $stationB);
        $routeController->findTrains($goRoutes, $departureDate);
        $scheduleStart = $ticket->schedule->day_start . ' ' . $ticket->schedule->time_start;
        $hoursToDeparture = Carbon::now()->diffInHours(Carbon::parse($scheduleStart), false);
        $exchangePolicy = ExchangePolicy::where('min_hours', '<=', $hoursToDeparture)
            ->where('max_hours', '>=', $hoursToDeparture)
            ->first();
        if ($exchangePolicy) {
            $exchangeFee = $exchangePolicy->exchange_fee;
        } else {
            $exchangeFee = 1;
        }
        $ticket->exchange_fee = $exchangeFee;

        return view('pages.exchange-search', [
            'ticket' => $ticket,
            'goRoutes' => $goRoutes,
            'departureDate' => $departureDate
        ]);
    }

    public function createExchange(Request $request)
    {
        $oldTicketId = $request->input('old_ticket_id');
        $newTrainId = $request->input('new_train_id');
        $departureDate = $request->input('departure_date');
        $departureTime = $request->input('departure_time');
        $arrivalDate = $request->input('arrival_date');
        $arrivalTime = $request->input('arrival_time');
        $customerId = $request->input('customer_id');
        $seat_number = $request->input('seat_number');
        $car_name = $request->input('car_name');
        $paymentMethod = $request->input('payment_method');
        DB::beginTransaction();

        try {
            // Lấy vé cũ và thông tin khách hàng
            $oldTicket = Ticket::findOrFail($oldTicketId);
            $customer = Customer::findOrFail($customerId);

            $newSchedule = Schedule::create([
                'train_id' => $newTrainId,
                'train_mark' => $oldTicket->schedule->train_mark,
                'day_start' => $departureDate,
                'time_start' => $departureTime,
                'day_end' => $arrivalDate,
                'time_end' => $arrivalTime,
                // 'station_start' => $newStationStart,
                // 'station_end' => $newStationEnd,
                'seat_number' => $oldTicket->schedule->seat_number,
                'car_name' => $oldTicket->schedule->car_name
            ]);

            // Tạo một booking mới
            $booking = Booking::create([
                'customer_id' => $customerId,
                'discount_price' => $oldTicket->discount_price,
                'booked_time' => now(),
                'booking_status' => 'booked',
                'total_price' => $oldTicket->price, // Tổng giá vé mới
                'payment_method' => $paymentMethod
            ]);

            // Tạo vé mới dựa trên lịch trình mới
            $newTicket = Ticket::create([
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
                'schedule_id' => $newSchedule->id,
                'price' => $oldTicket->price, // Giá của vé mới (có thể tính lại nếu cần)
                'discount_price' => $oldTicket->discount_price, // Giảm giá tương tự vé cũ
                'ticket_status' => 'new'
            ]);

            // Tạo bản ghi Exchange để ghi nhận việc đổi vé
            $exchange = Exchange::create([
                'old_ticket_id' => $oldTicketId,
                'new_ticket_id' => $newTicket->id,
                'booking_id' => $booking->id,
                'customer_id' => $customerId,
                'payment_method' => $paymentMethod,
                'old_price' => $oldTicket->price,
                'new_price' => $oldTicket->price, // Điều chỉnh nếu có thay đổi giá
                'additional_price' => 0, // Nếu không có phí thêm
                'exchange_status' => 'processed',
                'exchange_time' => now(),
                'exchange_time_processed' => now()
            ]);

            // Commit giao dịch
            DB::commit();

            return response()->json([
                'message' => 'Đổi vé và tạo lịch trình mới thành công!',
                'new_ticket' => $newTicket,
                'new_schedule' => $newSchedule,
                'exchange' => $exchange
            ], 200);
        } catch (\Exception $e) {
            // Nếu có lỗi thì rollback giao dịch
            DB::rollBack();
            return response()->json([
                'error' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    public function verifyConfirmation(Request $request)
    {
        $request->validate([
            'confirmation_code' => 'required|string|min:6|max:8',
        ]);

        $storedCode = session('confirmation_code');
        $exchange_id = session('exchange_id');

        if ($storedCode && $request->confirmation_code === $storedCode) {
            session()->forget('confirmation_code');
            session()->forget('exchange_id');

            $exchange = Exchange::find($exchange_id);

            if ($exchange) {
                $exchange->exchange_status = 'completed';
                $exchange->save();
            }

            $newTicket = Ticket::find($exchange->new_ticket_id);
            if ($newTicket) {
                $newTicket->booking_id = $exchange->booking_id;
                $newTicket->save();
            }

            return redirect()->route('exchange.success', ['exchange_id' => $exchange_id])->with('message', 'Xác nhận thành công!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Mã xác nhận không chính xác.']);
        }
    }

    public function success($exchange_id)
    {
        $exchange = Exchange::with('booking')->find($exchange_id);
        Mail::to($exchange->booking->customer->email)->send(new ExchangeSuccessMail($exchange));
        return view('pages.exchange-success', [
            'exchange' => $exchange
        ]);
    }

    public function showTransactionDetails($refund_id)
    {
        $refund = Refund::with('tickets')->find($refund_id);

        if (!$refund) {
            return redirect()->route('refund.success', ['refund_id' => $refund_id])->withErrors(['error' => 'Không có vé nào được hủy!']);
        }

        return view('pages.refund-details', [
            'refund' => $refund,
            'tickets' => $refund->tickets
        ]);
    }

    public function updateExchangeStatus()
    {
        $exchanges = exchange::whereIn('exchange_status', ['pending'])->get();

        foreach ($exchanges as $exchange) {
            if ($exchange->exchange_status === 'pending' && Carbon::parse($exchange->exchange_time)->diffInDays(Carbon::now()) > 3) {
                $exchange->exchange_status = 'rejected';
                $exchange->save();
            }
        }
        return response()->json(['message' => 'Cập nhật trạng thái hoàn vé thành công']);
    }

    //Api getAll
    public function index()
    {
        try {
            $exchanges = Exchange::all();
            return response()->json([
                'success' => true,
                'data' => $exchanges,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách đổi vé.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    //api getById
    public function show($id)
    {
        try {
            $exchange = Exchange::findOrFail($id);
            return response()->json($exchange);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Đổi vé không tìm thấy'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi lấy thông tin đổi vé: ' . $e->getMessage()], 500);
        }
    }

    //api update
    public function update(Request $request, $id)
    {
        try {
            $refund = Refund::findOrFail($id);

            $request->validate([
                'booking_id' => 'required|string|max:20',
                'refund_status' => 'required|string|max:20',
                'refund_time' => 'required|date',
                'customer_id' => 'required|exists:customers,id',
                'payment_method' => 'nullable|string|max:20',
                'refund_amount' => 'required|numeric',
                'refund_time_processed' => 'nullable|date',
            ]);

            $refund->booking_id = $request->input('booking_id');
            $refund->refund_status = $request->input('refund_status');
            $refund->refund_time = $request->input('refund_time');
            $refund->customer_id = $request->input('customer_id');
            $refund->payment_method = $request->input('payment_method');
            $refund->refund_amount = $request->input('refund_amount');
            $refund->refund_time_processed = $request->input('refund_time_processed');

            $refund->save();

            return response()->json([
                'message' => 'Hoàn tiền đã được cập nhật thành công!',
                'data' => $refund
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi cập nhật hoàn tiền: ' . $e->getMessage()], 500);
        }
    }

    //api destroy
    public function destroy($id)
    {
        try {

            $exchange = Exchange::findOrFail($id);

            $exchange->delete();

            return response()->json([
                'message' => 'Đổi vé đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi xóa đổi vé: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'booking_id' => 'required|string',
            'old_ticket_id' => 'required|integer',
            'new_ticket_id' => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        $booking = Booking::where('id', $request->booking_id)->first();

        $oldTicket = Ticket::where('id', $request->old_ticket_id)
            ->whereNull('exchange_id')
            ->where('booking_id', $booking->id)
            ->first();

        $newTicket = Ticket::where('id', $request->new_ticket_id)->first();

        $newPrice = $newTicket->price * (1 - $newTicket->discount_price);
        $scheduleStart = $oldTicket->schedule->day_start . ' ' . $oldTicket->schedule->time_start;
        $hoursToDeparture = Carbon::now()->diffInHours(Carbon::parse($scheduleStart), false);
        $exchangePolicy = ExchangePolicy::where('min_hours', '<=', $hoursToDeparture)
            ->where('max_hours', '>=', $hoursToDeparture)
            ->first();
        if ($exchangePolicy) {
            $exchangeFee = $exchangePolicy->exchange_fee;
        } else {
            $exchangeFee = 1;
        }
        $oldPrice = $newTicket->price * (1 - $oldTicket->discount_price - $exchangeFee);
        $additional_price = max(0, $newPrice - $oldPrice);
        $exchangeDate = Carbon::now();

        $exchange = Exchange::create([
            'old_ticket_id' => $oldTicket->id,
            'new_ticket_id' => $newTicket->id,
            'booking_id' => $booking->id,
            'exchange_status' => 'completed',
            'exchange_time' => $exchangeDate,
            'exchange_time_processed' => $exchangeDate,
            'customer_id' => $booking->customer_id,
            'payment_method' => $request->payment_method,
            'additional_price' => $additional_price,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
        ]);

        $oldTicket->update(['exchange_id' => $exchange->id]);

        // return redirect()->route('admin.refund.index')->with('success', 'Hoàn vé đã được tạo thành công.');
        return redirect()->back()->with('success', 'Đổi vé thành công.');
    }
}
