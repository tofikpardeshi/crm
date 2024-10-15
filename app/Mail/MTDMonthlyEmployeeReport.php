<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MTDMonthlyEmployeeReport extends Mailable
{
    use Queueable, SerializesModels;

    public $employee;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($employee)
    {
        //
        $this->employee = $employee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
 

        $date = \Carbon\Carbon::now(); // Replace this with your actual date variable

        $Monthly = 'Agent Wise Monthly Performance Tracker( MTD) for ' . $date->format('F-Y'); // F represents the full month name, Y represents the four-digit  

        // $Monthly = 'Monthly Mail Reports'; // Current date
        $yesterday = now()->subDay()->format('Y-m-d'); // Previous day
        
        $ccEmail = \DB::table('users')
        ->where('roles_id', 11)
        ->pluck('email');
        

        $empMails = \DB::table('employees')
        ->where('organisation_leave',0)
        ->where('role_id','!=',1)
        ->where('role_id','!=',11)
        ->pluck('official_email','user_id')
        ->toArray();

        // $AuthUser = \DB::table('employees')->where('user_id',\Auth::user()->id)->first();

       

        $employee = \DB::table('leads')
            ->whereMonth('leads.created_at', '=', now()->month) // Filter by the current month
            ->orderBy('created_at', 'asc') 
            ->get();
 

        return $this->markdown('emails.MTDMonthlyEmployeeReport')
        ->with('employee', $this->employee)
        ->subject($Monthly)
        ->cc('arvind@coretechies.com','tofik@coretechies.com'); 

        
    }
}
