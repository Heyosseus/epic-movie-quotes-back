<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;

class RecoveryPasswordMail extends Mailable
{
	use Queueable, SerializesModels;

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
		return $this->view('emails.recovery', ['token' => $this->token])
			->subject('Reset your password')
			->with(['token' => $this->token]);
	}
}
