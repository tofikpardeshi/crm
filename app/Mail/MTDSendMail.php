<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use DB;

class MTDSendMail extends Mailable
{
    use Queueable, SerializesModels;
    public $MTDSendMail;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($MTDSendMail)
    {
        //
        $this->MTDSendMail = $MTDSendMail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $today = 'Agent Wise Daily Performance Tracker Dated ' .now()->format('d-m-y'); // Current date
        $yesterday = now()->subDay()->format('Y-m-d'); // Previous day
        
        $ccEmail = \DB::table('users')->where('roles_id', 11)->pluck('email');
        

        $empMails = \DB::table('employees')
        ->where('organisation_leave',0)
        ->where('role_id','!=',1)
        ->where('role_id','!=',11)
        ->pluck('official_email','user_id')
        ->toArray();

        // $AuthUser = \DB::table('employees')->where('user_id',\Auth::user()->id)->first();

        // dd($AuthUser);


        $MTDSendMail = \DB::table('leads')
            ->whereDate('leads.created_at', $yesterday)
            ->orderBy('created_at', 'asc') 
            ->get();
 

        return $this->markdown('emails.MTDSendMail')
        ->with('MTDSendMail', $this->MTDSendMail)
        ->subject($today)
        ->cc($ccEmail,'arvind@coretechies.com','tofik@coretechies.com');
    }
}
