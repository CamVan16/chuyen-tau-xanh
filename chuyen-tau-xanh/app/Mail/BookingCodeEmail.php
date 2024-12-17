<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCodeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $bookings;

    public function __construct($bookings)
    {
        $this->bookings = $bookings;
    }

    public function build()
    {
        return $this->subject('Danh sách mã đặt chỗ')
                    ->view('emails.bookingCode')  // Chỉ định view của email
                    ->with([
                        'bookings' => $this->bookings,  // Truyền danh sách bookings vào view
                    ]);
    }
}
