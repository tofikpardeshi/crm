<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MTDMonthlyRepot extends Mailable
{
    use Queueable, SerializesModels;

    public $MTDMonthlyReport;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($MTDMonthlyReport)
    {
        //
        $this->MTDMonthlyReport = $MTDMonthlyReport; 
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $date = \Carbon\Carbon::now(); // Replace this with your actual date variable

        $Monthly = 'Agent Wise Monthly Performance Tracker( MTD) for '. $date->format('F-Y'); // F represents the full month name, Y represents the four-digit  

        $ccEmail = \DB::table('users')->where('roles_id', 11)->pluck('email');

        return $this->markdown('emails.MTDMonthlyReport')
        ->with('MTDMonthlyReport', $this->MTDMonthlyReport)
        ->subject($Monthly)
        ->cc($ccEmail,'arvind@coretechies.com','tofik@coretechies.com');
    }
}
