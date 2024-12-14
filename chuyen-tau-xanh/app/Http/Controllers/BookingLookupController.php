<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Customer;
use App\Mail\BookingCodeEmail;
use Illuminate\Support\Facades\Mail;

class BookingLookupController extends Controller
{
    // Hiển thị form tra cứu
    public function showForm()
    {
        return view('pages.lookup-booking');
    }

    // Xử lý tra cứu
    public function processLookup(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'booking_id' => 'required|string|regex:/^[A-Z0-9]+$/|max:10',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|regex:/^[0-9]{10,11}$/',
        ]);

        // Tìm thông tin booking kèm theo thông tin khách hàng và vé liên quan
        $booking = Booking::with(['customer', 'tickets'])
            ->where('id', $request->booking_id)
            ->whereHas('customer', function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->where('phone', $request->phone);
            })
            ->first();

        // Nếu không tìm thấy booking
        if (!$booking) {
            return back()->withErrors(['not_found' => 'Không tìm thấy thông tin đặt chỗ với dữ liệu đã cung cấp.']);
        }

        // Trả về view với dữ liệu
        return view('pages.lookup-booking-result', [
            'booking' => $booking, // Thông tin booking
        ]);
    }

    public function showBookingDetails($bookingId)
    {
        // Nạp dữ liệu Booking cùng các quan hệ tickets và customer của từng ticket
        $booking = Booking::with(['tickets.customer'])->findOrFail($bookingId);

        // Trả về view với dữ liệu
        return view('bookings.details', [
            'booking' => $booking,
        ]);
    }


    public function showForgotCodeForm()
    {
        return view('pages.forgot-booking-code');
    }

    public function sendBookingCode(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email',
        ]);

        // Tìm khách hàng theo email
        $customer = Customer::where('email', $request->email)->first();

        if (!$customer) {
            return back()->withErrors(['error' => 'Không tìm thấy khách hàng với email này.']);
        }

        $booking = Booking::where('customer_id', $customer->id)->first();;

        if (!$booking) {
            return redirect()->back()->withErrors(['error' => 'Không tìm thấy mã đặt chỗ liên quan đến email này.']);
        }

        // Gửi email với đối tượng booking
        Mail::to($customer->email)->send(new BookingCodeEmail($booking));

        return redirect()->back()->with('success', 'Mã đặt chỗ đã được gửi đến email của bạn.');
    }
}
