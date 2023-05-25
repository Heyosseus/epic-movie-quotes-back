<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the message content definition.
     */

    public function build(): Mailable
    {
        //		$token = route('email_verification_reset_password', ['token' => $this->token]);
        return $this->view('emails.activation', ['token' => $this->token])
            ->subject('Please verify your email address')
            ->with(['token' => $this->token]);
    }
}
