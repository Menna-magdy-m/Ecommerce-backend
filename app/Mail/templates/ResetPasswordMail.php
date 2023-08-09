<?php

namespace App\Mail\templates;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $reset_code;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $reset_code)
    {
        $this->reset_code = $reset_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Reset Password Email')->view('emails.reset-password-mail');
    }
}
