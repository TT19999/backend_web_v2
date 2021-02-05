<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Order extends Mailable
{
    use Queueable, SerializesModels;

    public $trip;
    public $user;
    public $owner;
    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($trip , $user,$owner,$order)
    {
        $this->trip=$trip;
        $this->user=$user;
        $this->owner=$owner;
        $this->order=$order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.order');
    }
}
