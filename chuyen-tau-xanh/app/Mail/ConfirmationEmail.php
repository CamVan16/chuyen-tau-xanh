<?php
namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;


    public $data;


    public function __construct($data)
    {
        $this->data = $data;
    }


    public function build()
    {
        return $this->view('emails.confirmation')
                    ->with(['data' => $this->data]);
    }
}
