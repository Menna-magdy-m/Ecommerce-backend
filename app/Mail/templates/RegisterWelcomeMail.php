<?php

namespace App\Mail\templates;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;


    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "Welcome to {$this->user->user_login} Theme API App";
        return $this
            ->subject($subject)
            ->view('emails.register-welcome');
    }
}
