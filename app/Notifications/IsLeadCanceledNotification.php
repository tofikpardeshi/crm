<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IsLeadCanceledNotification extends Notification
{
    use Queueable;
    public $isCancel;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($isCancel)
    {
        //
        $this->isCancel = $isCancel;
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

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
            'lead_name' => $this->isCancel['leads'],
            'property_requirement' => $this->isCancel['property_requirement'],
            'location' => $this->isCancel['location'],
            'number_of_units' => $this->isCancel['number_of_units'],
            'budget' =>  $this->isCancel['budget'],
            'EmployeeName' => $this->isCancel['EmployeeName'],
            'leads_status' => $this->isCancel['leads_status'],
            'leadsID' => $this->isCancel['leadsID'],
            'privios_emp' => $this->isCancel['privios_emp'],
            'current_empid' => $this->isCancel['current_empid']
        ];
    }
}
