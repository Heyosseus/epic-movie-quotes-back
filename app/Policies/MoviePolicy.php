<?php

namespace App\Policies;

use App\Models\User;
use App\Models\movie;
use Illuminate\Auth\Access\HandlesAuthorization;

class MoviePolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the model.
	 */
	public function index(User $user): bool
	{
		return $user->id === auth()->user()->id;
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
	public function update(User $user, Movie $movie): bool
	{
		return $user->id === $movie->user_id;
	}

	/**
	 * Determine whether the user can delete the model.
	 */
	public function delete(User $user, Movie $movie): bool
	{
		return $user->id === $movie->user_id;
	}
}
