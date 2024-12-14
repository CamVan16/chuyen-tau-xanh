<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function showInfo(Request $request)
    {
        $status = $request->query('status');
        $bookingInfo = $request->query('booking');
        $booking = json_decode(base64_decode($bookingInfo), true);
        $payment = $request->query('payment_method');
        if ($status === 'success') {
            $this->storeTransactionData($booking, $payment);
        }
        return view('pages.transaction_information', compact('status', 'booking', 'payment'));
    }

    private function storeTransactionData($bookingInfo, $payment)
    {
        $data = json_decode($bookingInfo, true);
        // $data = $bookingInfo;
        // 1. Lưu thông tin người đặt vé (booker)
        $booker = $data['booker'];
        $customer = Customer::create([
            'customer_name' => $booker['name'],
            'email' => $booker['email'] ?? null,
            'citizen_id' => $booker['citizen'],
            'phone' => $booker['phone']
        ]);
        // 2. Lưu thông tin booking
        try {
            $booking = Booking::create([
                // 'id' => $this->generateCustomString('BK'),
                'customer_id' => $customer->id,
                'total_price' => $data['amount'],
                'booked_time' => Carbon::now(),
                'booking_status' => 1,
                'payment_method' => $payment
            ]);
        } catch (\Exception $e) {
            \Log::error('Error while creating booking: ' . $e->getMessage());
        }

        // 3. Lưu thông tin các vé
        foreach ($data['tickets'] as $ticket) {
            $passenger = Customer::create([
                'customer_name' => $ticket['passenger_name'],
                'customer_type' => $ticket['customer_type'],
                'citizen_id' => $ticket['passenger_citizen']
            ]);
            $schedule = Schedule::create([
                'train_id' => $ticket['train_id'],
                'train_mark' => $ticket['train_mark'],
                'day_start' => $ticket['day_start'],
                'time_start' => $ticket['time_start'],
                'day_end' => $ticket['day_end'],
                'time_end' => $ticket['time_end'],
                'station_start' => $ticket['station_start'],
                'station_end' => $ticket['station_end'],
                'seat_number' => $ticket['seat_number'],
                'car_name' => $ticket['car_name']
            ]);
            $ticket = Ticket::create([
                // 'id' => $this->generateCustomString('TK'),
                'booking_id' => $booking->id,
                'customer_id' => $passenger->id,
                'schedule_id' => $schedule->id,
                'price' => $ticket['ticket_price'],
                'discount_price' => $ticket['discount'],
                'ticket_status' => 1
            ]);
        }
    }

}
