<?php

namespace App\Mail\templates;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $nicename;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $nicename)
    {
        $this->nicename = $nicename;
    }

    // To receive user instance


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Welcome to our Theme API App";
        return $this->subject($subject)->view('emails.welcome');
    }
}
