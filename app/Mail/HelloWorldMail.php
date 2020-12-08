<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;

class HelloWorldMail extends Mailable
{
    use Queueable, SerializesModels;
    public $trip;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($trip,$user)
    {
       $this->trip=$trip;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.hello');
    }
}
