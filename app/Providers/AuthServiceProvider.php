<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Quotes;
use App\Policies\QuotePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The model to policy mappings for the application.
	 *
	 * @var array<class-string, class-string>
	 */
	protected $policies = [
		Quotes::class => QuotePolicy::class,
	];

	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		$this->registerPolicies();
	}
}
