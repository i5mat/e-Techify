<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class JobApproved extends Mailable
{
    use Queueable, SerializesModels;
    public $getJob, $getEmployer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($getJob, $getEmployer)
    {
        $this->getJob = $getJob;
        $this->getEmployer = $getEmployer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.job-approved');
    }
}
