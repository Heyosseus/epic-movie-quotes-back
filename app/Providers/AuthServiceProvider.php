<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Movie;
use App\Models\Quote;
use App\Policies\MoviePolicy;
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
		Quote::class => QuotePolicy::class,
		Movie::class => MoviePolicy::class,
	];

	/**
	 * Register any authentication / authorization services.
	 */
	public function boot(): void
	{
		$this->registerPolicies();
	}
}
