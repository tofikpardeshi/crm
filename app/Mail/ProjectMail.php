<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectMail extends Mailable
{
    use Queueable, SerializesModels;
    public $ProjectDetails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ProjectDetails)
    { 
        $this->ProjectDetails = $ProjectDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.ProjectMail')->with('ProjectDetails', $this->ProjectDetails);;
    }
}
