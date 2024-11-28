<?php

namespace App\Mail;

use App\Models\Exchange;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExchangeSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $exchange;

    /**
     * Create a new message instance.
     *
     * @param Exchange $Exchange
     */
    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('ĐỔI VÉ THÀNH CÔNG')
            ->view('emails.exchange-success')
            ->with([
                'exchange' => $this->exchange,
            ]);
    }
}
