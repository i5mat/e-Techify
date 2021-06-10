<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $getData, $orderInfo, $recipientInfo;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($getData, $orderInfo, $recipientInfo)
    {
        $this->getData = $getData;
        $this->orderInfo = $orderInfo;
        $this->recipientInfo = $recipientInfo;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.confirm-ordermail');
    }
}
