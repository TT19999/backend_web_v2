<?php

namespace App\Notifications;

use App\Models\User_info;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoicePaid extends Notification
{
    use Queueable;
    public $user;
    public $trip;
    public $owner;
    public $order;
    public $user_info;
    public $owner_info;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($trip , $user, $owner, $order)
    {
        $this->trip=$trip;
        $this->user=$user;
        $this->owner=$owner;
        $this->order=$order;
        $this->user_info=User_info::where('user_id','=',$user->id)->first();
        $this->owner_info=User_info::where('user_id','=',$owner->id)->first();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
        // 'mail',
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->view('emails.order',['order'=> $this->order,'user'=>$this->user,'owner'=>$this->owner,'trip'=>$this->trip,'user_info'=>$this->user_info,'owner_info'=>$this->owner_info]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if($notifiable->id == $this->owner->id){
            $array= 'Trip: '. $this->trip->name . ' da duoc dat boi ' .$this->user->name. ' vui long xem chi tiet trong email';
        }
        else $array= 'ban da dat thanh cong Trip: '.$this->trip->name.' cua '.$this->owner->name . ', ban vui long xem chi tiet trong email';
        return [
            $array
        ];
    }

    
}
