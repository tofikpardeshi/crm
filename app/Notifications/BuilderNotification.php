<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BuilderNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $bulder;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bulder)
    {
        $this->bulder = $bulder; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //$url = $this->$BulderDetails['lead_info'] ?? "" ; 
        $cc =$this->bulder['cc'];
        return (new MailMessage)
        ->subject($this->bulder['subject'])
        ->cc($cc)
        ->line($this->bulder['body'])
        ->line($this->bulder['line1'] ?? []) 
        ->line($this->bulder['line2'])
        ->line($this->bulder['line3']) 
        ->line($this->bulder['line4'])
        ->line($this->bulder['line5'])
        ->line($this->bulder['line6'])
        ->line($this->bulder['line7'])
        ->line($this->bulder['line8'])
        ->line($this->bulder['line9'])
        ->line($this->bulder['line10'])
        ->line($this->bulder['line11']) 
        
        //->action('Visit Existing Lead', $url);  
        ->line($this->bulder['thanks']);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
