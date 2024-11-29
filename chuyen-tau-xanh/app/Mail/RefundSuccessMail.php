<?php

namespace App\Mail;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RefundSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refund;

    /**
     * Create a new message instance.
     *
     * @param Refund $refund
     */
    public function __construct(Refund $refund)
    {
        $this->refund = $refund;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('HOÀN VÉ THÀNH CÔNG')
            ->view('emails.refund-success')
            ->with([
                'refund' => $this->refund,
            ]);
    }
}
