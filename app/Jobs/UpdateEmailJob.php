<?php

namespace App\Jobs;

use App\Mail\NewEmailMail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class UpdateEmailJob implements ShouldQueue
{
	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function handle()
	{
		Mail::to($this->user->email)->send(new NewEmailMail($this->user));
		$this->user->email_verified_at = now();
		$this->user->save();
	}
}
