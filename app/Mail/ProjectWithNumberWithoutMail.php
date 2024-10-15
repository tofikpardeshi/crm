<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectWithNumberWithoutMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ProjectRegister;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ProjectRegister)
    { 
        $this->ProjectRegister = $ProjectRegister;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    
    public function build()
    {
        return $this->subject($this->ProjectRegister['subject'])
                ->markdown('emails.projectmail', [
                    'ProjectRegister' => $this->ProjectRegister
                ]);
    }
}
