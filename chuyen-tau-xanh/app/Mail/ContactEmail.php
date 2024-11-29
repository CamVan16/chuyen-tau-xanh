<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function build()
    {
        return $this->view('emails.contact')
                    ->subject('Thông tin liên hệ từ: ' . $this->data['name'])
                    ->from('7radiante@gmail.com', 'Chuyến tàu xanh')
                    ->replyTo($this->data['email'], $this->data['name'])
                    ->with(['data' => $this->data]);
    }
}
