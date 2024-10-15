<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $details;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
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
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $cc = $this->details['cc'];
        
        $url = $this->details['lead_info'];
        // $url = url('https://crm.homents.in/'); 
        return (new MailMessage)
        ->subject($this->details['subject'])
        ->cc($cc)
        // ->greeting($this->details['greeting'])
        ->line($this->details['body'])
        ->action('Visit Existing Lead', $url);  
        // ->line($this->details['thanks']);
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
            'lead_name' => $this->details['leads'],
            'property_requirement' => $this->details['property_requirement'],
            'location' => $this->details['location'],
            'number_of_units' => $this->details['number_of_units'].' Unit',
            'budget' =>  $this->details['budget'],
            'EmployeeName' => $this->details['EmployeeName'],
            'leads_status' => $this->details['leads_status'],
            'leadsID' => $this->details['leadsID'],
            'privios_emp' => $this->details['privios_emp'],
            'current_empid' => $this->details['current_empid'],
            'message' => $this->details['message'] ?? null,
            'messageUpdateBy' =>  $this->details['messageUpdateBy'] ?? null,
            'co_follow_up' => $this->details['co_follow_up'] ?? null,
        ];
    }
}
