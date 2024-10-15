<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $backupFileName;
    public function __construct($backupFileName)
    {
        $this->backupFileName = $backupFileName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // \Log::info('Attempting to send backup email.');
        return $this->view('emails.backup')
            ->attach(storage_path('app/backup/' . $this->backupFileName));
    }
}
