<?php

namespace App\Http\Controllers;

// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use App\Mail\BookingCodeEmail;
use App\Mail\ExchangeConfirmation;
use App\Mail\ExchangeSuccessMail;
use App\Mail\RefundConfirmation;
use App\Mail\RefundSuccessMail;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\Exchange;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

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

    public function getPageExchangeStep2()
    {
        return view('pages.exchange-verify');
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
            return redirect()->route('exchange.selection', ['booking_id' => $booking->id])
                ->with('warning', 'Không có vé nào có thể đổi cho mã đặt vé này.');
        }

        return view('exchange-selection', [
            'booking' => $booking,
            'tickets' => $tickets
        ]);
    }

    public function createExchange(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
            'old_ticket_id' => 'required|integer',
            'new_ticket_id' => 'required|integer',
            'payment_method' => 'required|string',
        ]);

        $booking = Booking::where('id', $request->booking_id)->first();

        if (!$booking) {
            return redirect()->back()->withErrors(['error' => 'Thông tin mã đặt chỗ không chính xác.']);
        }

        $oldTicket = Ticket::where('id', $request->old_ticket_id)
            ->whereNull('exchange_id')
            ->where('booking_id', $booking->id)
            ->first();

        if (!$oldTicket) {
            return redirect()->back()->withErrors(['error' => 'Vé cũ không hợp lệ hoặc đã được đổi.']);
        }

        $newTicket = Ticket::where('id', $request->new_ticket_id)->first();

        if (!$newTicket) {
            return redirect()->back()->withErrors(['error' => 'Vé mới không tồn tại.']);
        }

        $newPrice = $newTicket->price*(1-$newTicket->discount_price - 0.1);
        $oldPrice = $newTicket->price*(1-$oldTicket->discount_price);
        $additional_price = max(0, $newPrice - $oldPrice);
        $exchangeDate = Carbon::now();

        $exchange = Exchange::create([
            'old_ticket_id' => $oldTicket->id,
            'new_ticket_id' => $newTicket->id,
            'booking_id' => $booking->id,
            'exchange_status' => 'pending',
            'exchange_date' => $exchangeDate,
            'customer_id' => $booking->customer_id,
            'payment_method' => $request->payment_method,
            'additional_price' => $additional_price,
            'old_price' => $oldPrice,
            'new_price' => $newPrice,
        ]);

        $oldTicket->update(['exchange_id' => $exchange->id]);

        $confirmation_code = Str::random(6);

        session(['exchange_id' => $exchange->id, 'confirmation_code' => $confirmation_code]);

        $details = [
            'subject' => 'Mã xác nhận đổi vé',
            'booking_id' => $booking->id,
            'confirmation_code' => $confirmation_code,
        ];

        try {
            $customer = $booking->customer;
            Mail::to($customer->email)->send(new ExchangeConfirmation($details));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Có lỗi xảy ra khi gửi email xác nhận.']);
        }

        return view('pages.exchange-verify');
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
                $$exchange->$exchange_status = 'confirmed';
                $$exchange->save();
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

    // public function updateRefundStatus()
    // {
    //     $refunds = Refund::whereIn('refund_status', ['pending', 'confirmed'])->get();

    //     foreach ($refunds as $refund) {
    //         if ($refund->refund_status === 'pending' && Carbon::parse($refund->refund_date)->diffInDays(Carbon::now()) > 3) {
    //             $refund->refund_status = 'rejected';
    //             $refund->save();
    //         }

    //         if ($refund->refund_status === 'confirmed' && Carbon::parse($refund->refund_date)->diffInDays(Carbon::now()) > 3) {
    //             $refund->refund_status = 'completed';
    //             $refund->refund_date_processed = Carbon::now();
    //             $refund->save();
    //         }
    //     }
    //     return response()->json(['message' => 'Refund statuses updated successfully.']);
    // }
    public function updateExchangeStatus()
    {
        $exchanges = exchange::whereIn('exchange_status', ['pending', 'confirmed'])->get();

        foreach ($exchanges as $exchange) {
            if ($exchange->exchange_status === 'pending' && Carbon::parse($exchange->exchange_date)->diffInDays(Carbon::now()) > 3) {
                $exchange->exchange_status = 'rejected';
                $exchange->save();
            }

            if ($exchange->exchange_status === 'confirmed' && !$exchange->payment_method && Carbon::parse($exchange->exchange_date)->diffInDays(Carbon::now()) > 3) {
                $exchange->exchange_status = 'rejected';
                $exchange->save();
            }
            if ($exchange->exchange_status === 'confirmed' && $exchange->payment_method && Carbon::parse($exchange->exchange_date)->diffInDays(Carbon::now()) < 3) {
                $exchange->exchange_status = 'completed';
                $exchange->exchange_date_processed = 'completed';
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
                'refund_date' => 'required|date',
                'customer_id' => 'required|exists:customers,id',
                'payment_method' => 'nullable|string|max:20',
                'refund_amount' => 'required|numeric',
                'refund_date_processed' => 'nullable|date',
            ]);

            $refund->booking_id = $request->input('booking_id');
            $refund->refund_status = $request->input('refund_status');
            $refund->refund_date = $request->input('refund_date');
            $refund->customer_id = $request->input('customer_id');
            $refund->payment_method = $request->input('payment_method');
            $refund->refund_amount = $request->input('refund_amount');
            $refund->refund_date_processed = $request->input('refund_date_processed');

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

        $newPrice = $newTicket->price*(1-$newTicket->discount_price - 0.1);
        $oldPrice = $newTicket->price*(1-$oldTicket->discount_price);
        $additional_price = max(0, $newPrice - $oldPrice);
        $exchangeDate = Carbon::now();

        $exchange = Exchange::create([
            'old_ticket_id' => $oldTicket->id,
            'new_ticket_id' => $newTicket->id,
            'booking_id' => $booking->id,
            'exchange_status' => 'completed',
            'exchange_date' => $exchangeDate,
            'exchange_date_processed' => $exchangeDate,
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
