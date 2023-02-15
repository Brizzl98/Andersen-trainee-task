<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

use function Termwind\renderUsing;


class ResetPasswordMailer extends Mailable
{
    use Queueable, SerializesModels;
    public $token;
    public function __construct( $reset)
    {
        $this->token = $reset['token'];
    }
    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Reset Password',
        );
    }
    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
//    public function content()
//    {
////        $resetToken = $this->token;
////        $message = "Your password reset token is: $resetToken";
////
////        $content = new Content();
////        $content->text($message);
////
////        return $content;
//    }

    public function build(){
        return $this
            ->from("my@mail.com")
            ->view("mails.resetPassword")
            ->subject($this->subject)
            ->with(["token"=>$this->token]);
    }
}
