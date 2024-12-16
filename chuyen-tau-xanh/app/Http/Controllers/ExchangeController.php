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
        $tickets = Ticket::where('booking_id', $booking->id)
            ->where('ticket_status', 1)
            ->get();
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
            ->where('ticket_status', 1)
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
        $customer = Customer::where('id', $ticket->customer_id)->first();
        $discount_percent = 0;
        if ($customer) {
            switch ($customer->customer_type) {
                case 'Trẻ em':
                    $discount_percent = 25;
                    break;
                case 'Sinh viên':
                    $discount_percent = 10;
                    break;
                case 'Người cao tuổi':
                    $discount_percent = 15;
                    break;
                case 'Đoàn viên Công Đoàn':
                    $discount_percent = 5;
                    break;
                default:
                    $discount_percent = 0;
                    break;
            }
        }
        $ticket->discount_percent = $discount_percent;
        return view('pages.exchange-search', [
            'ticket' => $ticket,
            'goRoutes' => $goRoutes,
            'departureDate' => $departureDate
        ]);
    }

    public function exchangeTicket(Request $request)
    {
        $data = $request->json()->all();

        $scheduleData = $data['schedule'];
        $ticketData = $data['ticket'];
        $oldTicketData = $data['oldTicket'];

        // Tạo lịch trình mới
        $schedule = Schedule::create([
            'train_id' => $scheduleData['train_id'],
            'train_mark' => $scheduleData['train_mark'],
            'day_start' => $scheduleData['day_start'],
            'time_start' => $scheduleData['time_start'],
            'day_end' => $scheduleData['day_end'],
            'time_end' => $scheduleData['time_end'],
            'station_start' => $scheduleData['station_start'],
            'station_end' => $scheduleData['station_end'],
            'seat_number' => $scheduleData['seat_number'],
            'car_name' => $scheduleData['car_name'],
        ]);

        // Kiểm tra nếu oldTicket hợp lệ
        if (!isset($oldTicketData['customer_id'])) {
            return response()->json(['message' => 'Old ticket data is invalid.'], 400);
        }

        // Kiểm tra booking cũ
        $oldBooking = Booking::where('id', $oldTicketData['booking_id'])->first();
        if (!$oldBooking) {
            return response()->json(['message' => 'Old booking not found.'], 404);
        }

        if (!isset($oldTicketData['customer_id'])) {
            return response()->json(['message' => 'customer_id is missing in ticket data.'], 400);
        }

        // Lấy customer_id từ ticketData
        $customerId = $oldTicketData['customer_id'];
        $customerBookingId = $oldBooking->customer_id;

        // Kiểm tra thông tin khách hàng
        $customer = Customer::where('id', $customerId)->first();
        if (!$customer) {
            return response()->json(['message' => 'Customer not found.'], 404);
        }

        // Tính toán phần trăm giảm giá
        $discount_percent = 0;
        switch ($customer->customer_type) {
            case 'Trẻ em':
                $discount_percent = 0.25;
                break;
            case 'Sinh viên':
                $discount_percent = 0.10;
                break;
            case 'Người cao tuổi':
                $discount_percent = 0.15;
                break;
            case 'Đoàn viên Công Đoàn':
                $discount_percent = 0.5;
                break;
            default:
                $discount_percent = 0;
                break;
        }

        // Tính số giờ còn lại trước khi chuyến đi
        $scheduleStart = $oldTicketData['schedule']['day_start'] . ' ' . $oldTicketData['schedule']['time_start'];
        $hoursToDeparture = Carbon::now()->diffInHours(Carbon::parse($scheduleStart), false);

        // Lấy phí đổi vé
        $exchangePolicy = ExchangePolicy::where('min_hours', '<=', $hoursToDeparture)
            ->where('max_hours', '>=', $hoursToDeparture)
            ->first();
        $exchangeFee = $exchangePolicy ? $exchangePolicy->exchange_fee : 1;

        // Tạo booking mới
        $booking = Booking::create([
            'customer_id' => $customerBookingId,
            'booked_time' => now(),
            'booking_status' => 1,
            'total_price' => ($ticketData['price'] * (1 - $discount_percent)),
            'payment_method' => $oldBooking->payment_method,
        ]);

        // Tạo vé mới
        $newTicket = Ticket::create([
            'booking_id' => $booking->id,
            'customer_id' => $customerId,
            'schedule_id' => $schedule->id,
            'price' => $ticketData['price'],
            'discount_price' => ($ticketData['price'] * ($discount_percent)),
            'ticket_status' => 1,
        ]);

        // Tạo thông tin đổi vé
        $exchange = Exchange::create([
            'old_ticket_id' => $oldTicketData['id'],
            'new_ticket_id' => $newTicket->id,
            'booking_id' => $oldBooking->id,
            'customer_id' => $customerId,
            'payment_method' => $oldBooking->payment_method,
            'old_price' => $oldTicketData['price'] - $oldTicketData['discount_price'],
            'new_price' => ($ticketData['price'] * (1 - $discount_percent)),
            'additional_price' => ($ticketData['price'] * (1 - $discount_percent)) - ($oldTicketData['price'] - $oldTicketData['discount_price']) + ($oldTicketData['price'] * $exchangeFee),
            'exchange_status' => 'completed',
            'exchange_time' => now(),
            'exchange_time_processed' => now(),
        ]);

        // Cập nhật trạng thái vé cũ
        $oldTicket = Ticket::find($oldTicketData['id']);
        if ($oldTicket) {
            $oldTicket->exchange_id = $exchange->id;
            $oldTicket->ticket_status = -1;
            $oldTicket->save();
        }

        $newExchange = $exchange;

        $newTicketId = $newExchange->new_ticket_id;

        $ticket = Ticket::find($newTicketId);

        if ($ticket) {
            $bookingId = $ticket->booking_id;
            $newExchange->newBookingId = $bookingId;
        }
        $booking = Booking::find($newExchange->booking_id);
        $customer = Customer::find($booking->customer_id);
        Mail::to($customer->email)->send(new \App\Mail\ExchangeSuccessMail($newExchange));
        session()->put('message', 'Đổi vé thành công');
        session()->put('exchange', $exchange);
        session()->put('newTicket', $newTicket);

        return response()->json([
            'message' => 'Đổi vé thành công!',
            'redirect_url' => route('exchange.success')
        ]);
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

        $newPrice = $newTicket->price - $newTicket->discount_price;
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
        $oldPrice = $newTicket->price * (1 - $exchangeFee) - $oldTicket->discount_price;
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

        return redirect()->back()->with('success', 'Đổi vé thành công.');
    }
    public function showSuccess(Request $request)
    {
        return view('pages.exchange-success-nonpay', [
            'message' => $request->session()->get('message'),
            'exchange' => $request->session()->get('exchange'),
            'newTicket' => $request->session()->get('newTicket'),
        ]);
    }
}
