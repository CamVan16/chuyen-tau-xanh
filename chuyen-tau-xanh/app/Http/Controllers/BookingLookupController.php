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

        // Tìm thông tin booking
        $booking = Booking::select('bookings.*')
            ->join('customers', 'customers.id', '=', 'bookings.customer_id')
            ->where('bookings.id', $request->booking_id)
            ->where('customers.email', $request->email)
            ->where('customers.phone', $request->phone)
            ->first();


        // Nếu không tìm thấy
        if (!$booking) {
            return back()->withErrors(['not_found' => 'Không tìm thấy thông tin đặt chỗ với dữ liệu đã cung cấp.']);
        }

        // Trả về thông tin đặt chỗ
        return view('pages.lookup-booking-result', compact('booking'));
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
