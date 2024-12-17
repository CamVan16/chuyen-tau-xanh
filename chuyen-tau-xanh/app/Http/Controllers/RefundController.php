<?php

namespace App\Http\Controllers;

// use Carbon\Carbon;
use Illuminate\Support\Carbon;
use App\Mail\BookingCodeEmail;
use App\Mail\RefundConfirmation;
use App\Mail\RefundSuccessMail;
use Illuminate\Http\Request;
use App\Models\Refund;
use App\Models\Booking;
use App\Models\Customer;
use App\Models\RefundPolicy;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    public function getPageRefund()
    {
        return view('pages.refund');
    }

    public function getPageRefundStep1()
    {
        return view('pages.refund-selection');
    }

    public function getPageRefundStep2()
    {
        return view('pages.refund-verify');
    }

    public function findBooking(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
            'email' => 'required|string',
            'phone' => 'required|string',
        ]);

        $booking = Booking::where('id', $request->booking_id)
            ->first();

        if (!$booking) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Thông tin đặt chỗ không đúng, vui lòng kiểm tra lại.']);
        }

        $customer = Customer::where('email', $request->email)
        ->where('phone', $request->phone)
        ->where('id', $booking->customer_id)
        ->first();

        if (!$customer) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Thông tin vé cần trả không đúng, vui lòng kiểm tra lại']);
        }

        $tickets = $booking->tickets;
        $tickets = Ticket::where('booking_id', $booking->id)
            ->where('ticket_status', 1)
            ->get();
        if ($tickets->isEmpty()) {
            return redirect()->back()
                ->with('warning', 'Không có vé nào có thể trả cho mã đặt vé này.');
        }
        foreach ($tickets as $ticket) {
            $scheduleStart = $ticket->schedule->day_start . ' ' . $ticket->schedule->time_start;
            $hoursToDeparture = Carbon::now()->diffInHours(Carbon::parse($scheduleStart), false);
            $refundPolicy = RefundPolicy::where('min_hours', '<=', $hoursToDeparture)
                ->where('max_hours', '>=', $hoursToDeparture)
                ->first();
            if ($refundPolicy) {
                $refundFee = $refundPolicy->refund_fee;
            } else {
                $refundFee = 1;
            }
            $ticket->refund_fee = $refundFee;
        }

        return view('pages.refund-selection', ['booking' => $booking, 'tickets' => $tickets]);
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

        $customers = Customer::where('email', $email)->get();

        if ($customers->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy khách hàng với email này.']);
        }

        $bookings = collect();

        foreach ($customers as $customer) {
            $customerBookings = Booking::where('customer_id', $customer->id)->get();

            $bookings = $bookings->merge($customerBookings);
        }

        if ($bookings->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy mã đặt chỗ liên quan đến email này.']);
        }
        Mail::to($email)->send(new BookingCodeEmail($bookings));

        return redirect()->back()->with('success', 'Danh sách mã đặt chỗ đã được gửi tới email của bạn.');
    }


    public function createRefund(Request $request)
    {
        $request->validate([
            'booking_id' => 'required|string',
            'ticket_array' => 'required|array',
            'ticket_array.*' => 'string',
        ]);

        // Find the booking
        $booking = Booking::find($request->booking_id);

        $totalRefund = 0;
        $validTickets = [];

        foreach ($request->ticket_array as $ticket_id) {
            $ticket = Ticket::where('id', $ticket_id)
                ->whereNull('refund_id')
                ->where('booking_id', $booking->id)
                ->first();

            if ($ticket) {
                $scheduleStart = Carbon::parse($ticket->schedule->day_start . ' ' . $ticket->schedule->time_start);
                $hoursToDeparture = Carbon::now()->diffInHours($scheduleStart, false);

                $refundPolicy = RefundPolicy::where('min_hours', '<=', $hoursToDeparture)
                    ->where('max_hours', '>=', $hoursToDeparture)
                    ->first();

                $refundFee = $refundPolicy ? $refundPolicy->refund_fee : 1;
                $refundAmount = $ticket->price * (1 - $refundFee) - $ticket->discount_price;

                if ($refundAmount > 0) {
                    $totalRefund += $refundAmount;
                    $validTickets[] = $ticket;
                }
            }
        }

        $refund = Refund::create([
            'booking_id' => $booking->id,
            'refund_status' => 'pending',
            'refund_amount' => $totalRefund,
            'payment_method' => $booking->payment_method,
            'refund_time' => Carbon::now(),
            'refund_time_processed' => Carbon::now(),
            'customer_id' => $booking->customer_id,
        ]);

        foreach ($validTickets as $ticket) {
            $ticket->update(['refund_id' => $refund->id]);
        }

        $confirmation_code = Str::random(6);
        session(['refund_id' => $refund->id, 'confirmation_code' => $confirmation_code]);

        $details = [
            'subject' => 'Mã xác nhận hoàn vé',
            'booking_id' => $booking->id,
            'confirmation_code' => $confirmation_code,
        ];

        try {
            $customer = $booking->customer;
            Mail::to($customer->email)->send(new RefundConfirmation($details));
        } catch (\Exception $e) {
            Log::error('Error sending refund confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('refund.getPageRefundStep2')->with('message', 'Vui lòng kiểm tra email để xác nhận hoàn vé.');
    }

    public function verifyConfirmation(Request $request)
    {
        $request->validate([
            'confirmation_code' => 'required|string|min:6|max:8',
        ]);

        $storedCode = session('confirmation_code');
        $refund_id = session('refund_id');

        if ($storedCode && $request->confirmation_code === $storedCode) {
            session()->forget('confirmation_code');
            session()->forget('refund_id');

            $refund = Refund::find($refund_id);

            if ($refund) {
                $refund->refund_status = 'completed';
                $refund->save();
            }
            $ticket = Ticket::where('refund_id', $refund_id)->first();
            if ($ticket) {
                $ticket->ticket_status = $ticket->ticket_status ?? 1;
                $ticket->ticket_status = -1;
                $ticket->save();
            }

            return redirect()->route('refund.success', ['refund_id' => $refund_id])->with('message', 'Xác nhận thành công!');
        } else {
            return redirect()->back()->withErrors(['error' => 'Mã xác nhận không chính xác.']);
        }
    }

    public function success($refund_id)
    {
        $refund = Refund::with('booking')->find($refund_id);
        Mail::to($refund->booking->customer->email)->send(new RefundSuccessMail($refund));
        return view('pages.refund-success', [
            'refund' => $refund
        ]);
    }

    public function showTransactionDetails($refund_id)
    {
        $refund = Refund::with('tickets')->find($refund_id);

        if (!$refund) {
            return redirect()->route('refund.success', ['refund_id' => $refund_id])->withErrors(['error' => 'Không có vé nào được hủy!']);
        }

        $tickets = $refund->tickets;

        foreach ($tickets as $ticket) {
            $scheduleStart = $ticket->schedule->day_start . ' ' . $ticket->schedule->time_start;
            $hoursToDeparture = Carbon::now()->diffInHours(Carbon::parse($scheduleStart), false);
            $refundPolicy = RefundPolicy::where('min_hours', '<=', $hoursToDeparture)
                ->where('max_hours', '>=', $hoursToDeparture)
                ->first();
            if ($refundPolicy) {
                $refundFee = $refundPolicy->refund_fee;
            } else {
                $refundFee = 1;
            }
            $ticket->refund_fee = $refundFee;
        }


        return view('pages.refund-details', [
            'refund' => $refund,
            'tickets' => $tickets
        ]);
    }

    public function updateRefundStatus()
    {
        $refunds = Refund::whereIn('refund_status', ['pending'])->get();

        foreach ($refunds as $refund) {
            if ($refund->refund_status === 'pending' && Carbon::parse($refund->refund_time)->diffInDays(Carbon::now()) > 3) {
                $refund->refund_status = 'rejected';
                $refund->save();
            }
        }
        return response()->json(['message' => 'Refund statuses updated successfully.']);
    }

    //Api getAll
    public function index()
    {
        try {
            $refunds = Refund::all();
            return response()->json([
                'success' => true,
                'data' => $refunds,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi lấy danh sách hoàn vé.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getListRefund(Request $request)
    {
        $refundStatus = $request->input('refund_status', null);
        $bookingCode = $request->input('booking_id', null);
        $refundsQuery = Refund::query();
        if ($refundStatus) {
            $refundsQuery->where('refund_status', $refundStatus);
        }
        if ($bookingCode) {
            $refundsQuery->where('booking_id', 'LIKE', '%' . $bookingCode . '%');
        }
        $refunds = $refundsQuery->paginate(10);
        return response()->json($refunds);
    }

    //api getById
    public function show($id)
    {
        try {
            $refund = Refund::findOrFail($id);
            return response()->json($refund);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['error' => 'Hoàn vé không tìm thấy'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi lấy thông tin hoàn vé: ' . $e->getMessage()], 500);
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

            $refund = Refund::findOrFail($id);

            $refund->delete();

            return response()->json([
                'message' => 'Hoàn tiền đã được xóa thành công!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Có lỗi xảy ra khi xóa hoàn tiền: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {

        $request->validate([
            'booking_id' => 'required|string|exists:bookings,id',
            'ticket_array' => 'required|array',
            'ticket_array.*' => 'integer|exists:tickets,id',
        ]);

        $booking = Booking::find($request->booking_id);

        $tickets = Ticket::where('booking_id', $booking->id)
            ->where('ticket_status', 1)
            ->get();

        if ($tickets->isEmpty()) {
            return redirect()->back()->withErrors(['error' => 'Không có vé hợp lệ để hoàn.']);
        }

        $totalRefund = $tickets->sum(function ($ticket) {
            $hoursBeforeStart = Carbon::now()->diffInHours($ticket->schedule->day_start, false);

            $policy = RefundPolicy::where('min_hours', '<=', $hoursBeforeStart)
                ->where('max_hours', '>=', $hoursBeforeStart)
                ->first();

            $refundFee = $policy ? $policy->refund_fee : 1;

            return $ticket->price * (1 - $refundFee) - $ticket->discount_price;
        });

        $refund = Refund::create([
            'booking_id' => $booking->id,
            'refund_status' => 'completed',
            'refund_amount' => $totalRefund,
            'refund_time' => Carbon::now(),
            'customer_id' => $booking->customer_id,
        ]);

        $tickets->each(function ($ticket) use ($refund) {
            $ticket->update(['refund_id' => $refund->id]);
        });

        // return redirect()->route('admin.refund.index')->with('success', 'Hoàn vé đã được tạo thành công.');
        return redirect()->back()->with('success', 'Hoàn vé đã được tạo thành công.');
    }
}
