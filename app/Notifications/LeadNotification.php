<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeadNotification extends Notification
{
    use Queueable;
     public $leadnotydata;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($leadnotydata)
    { 
        $this->leadnotydata = $leadnotydata;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }
 
    public function toArray($notifiable)
    {
        return [
            'id' => $this->leadnotydata,
        ];
    }
}
