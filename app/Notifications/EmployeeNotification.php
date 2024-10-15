<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeNotification extends Notification
{
    use Queueable;
    public $employeeNotification;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($employeeNotification)
    {
        $this->employeeNotification = $employeeNotification;
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
            'employee_name' => $this->employeeNotification['employee_name'],
            'employeeID' => $this->employeeNotification['employeeID'] ?? "",
        ];
    }
}
