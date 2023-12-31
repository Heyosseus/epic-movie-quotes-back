<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Quote;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuotePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the model.
	 */
	public function index(): bool
	{
		return true;
	}

	/**
	 * Determine whether the user can create models.
	 */
	public function store(User $user): bool
	{
		return $user->id === auth()->user()->id;
	}

	/**
	 * Determine whether the user can update the model.
	 */
	public function update(User $user, Quote $quotes): bool
	{
		return $user->id === $quotes->user_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Quote $quotes): bool
	{
		return $user->id === $quotes->user_id;
	}
}
