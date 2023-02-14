<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class ResetPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    /**
     * Create a new message instance.
     *
     * @param string $token
     * @param string $email
     */
    public function __construct( $reset)
    {
        $this->token = $reset['token'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $subject = sprintf('Reset your password');

        return $this->to($this->email)
            ->subject($subject)
            ->text('Your password reset token is: ' . $this->token);
    }
}
