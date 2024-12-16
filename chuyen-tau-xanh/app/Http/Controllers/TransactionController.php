<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Booking;
use App\Models\Exchange;
use App\Models\ExchangePolicy;
use App\Models\Schedule;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class TransactionController extends Controller
{
    public function showInfoExchange(Request $request)
    {
        $status = $request->query('status');
        $exchangeInfo = $request->query('exchange');
        $exchange = json_decode(base64_decode($exchangeInfo), true);
        $payment = $request->query('payment_method');
        if ($status === 'success') {
            $this->storeTransactionDataExchange($exchange, $payment);
            $newExchange = session('newExchange');
            $newTicketId = $newExchange->new_ticket_id;

            $ticket = Ticket::find($newTicketId);

            if ($ticket) {
                $bookingId = $ticket->booking_id;
                $newExchange->newBookingId = $bookingId;
            }
            $booking = Booking::find($newExchange->booking_id);
            $customer = Customer::find($booking->customer_id);
            Mail::to($customer->email)->send(new \App\Mail\ExchangeSuccessMail($newExchange));
        }
        return view('pages.transaction_information_exchange', compact('status', 'newExchange', 'payment'));
    }

    public function storeTransactionDataExchange($exchangeInfo, $payment)
    {
        $data = json_decode($exchangeInfo, true);
        // $data = $request->json()->all();

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
            'payment_method' => $payment,
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
        $newExchange = Exchange::create([
            'old_ticket_id' => $oldTicketData['id'],
            'new_ticket_id' => $newTicket->id,
            'booking_id' => $oldBooking->id,
            'customer_id' => $customerId,
            'payment_method' => $payment,
            'old_price' => $oldTicketData['price'] - $oldTicketData['discount_price'],
            'new_price' => ($ticketData['price'] * (1 - $discount_percent)),
            'additional_price' => ($ticketData['price'] * (1 - $discount_percent)) - ($oldTicketData['price'] - $oldTicketData['discount_price']) + ($oldTicketData['price'] * $exchangeFee),
            'exchange_status' => 'completed',
            'exchange_time' => now(),
            'exchange_time_processed' => now(),
        ]);

        $oldTicket = Ticket::find($oldTicketData['id']);
        if ($oldTicket) {
            $oldTicket->exchange_id = $newExchange->id;
            $oldTicket->ticket_status = -1;
            $oldTicket->save();
        }
        session(['newExchange' => $newExchange]);
    }
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
