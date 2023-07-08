<?php

namespace App\Jobs;

use App\Mail\AccountActivationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	/**
	 * Create a new job instance.
	 */
	protected $user;

	public function __construct($user)
	{
		$this->user = $user;
	}

	/**
	 * Execute the job.
	 */
	public function handle()
	{
		Mail::to($this->user->email)->send(new AccountActivationMail($this->user));

		$this->user->email_verified_at = now();
		$this->user->save();
	}
}
