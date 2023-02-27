<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;


use function Termwind\renderUsing;


class DeleteMailer extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;

    public function __construct($pdf)
    {
        $this->pdf = $pdf;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            subject: 'Delete User',
        );
    }

    public function build()
    {
        return $this
            ->from("delete@mail.com")
            ->view('mails.delete')
            ->subject($this->subject)
            ->attachData($this->pdf->output(), "text.pdf");
    }
}
