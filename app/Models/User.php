<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
	use HasApiTokens;

	use HasFactory;

	use Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array<int, string>
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'email_verified_at',
		'profile_picture',
	];

	/**
	 * The attributes that should be hidden for serialization.
	 *
	 * @var array<int, string>
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast.
	 *
	 * @var array<string, string>
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
		'password'          => 'hashed',
	];

	use HasApiTokens, HasFactory, Notifiable;

	public function password(): Attribute
	{
		return Attribute::make(
			set: fn ($value) => bcrypt($value)
		);
	}

	public function movies(): HasMany
	{
		return $this->hasMany(Movie::class);
	}

	public function likes()
	{
		return $this->belongsToMany(Quote::class, 'quote_user', 'user_id', 'quote_id')
			->withPivot('likes')
			->withTimestamps();
	}

	public function quotes(): HasMany
	{
		return $this->hasMany(Quote::class, 'user_id');
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class, 'user_id');
	}

	public function notifications(): MorphMany
	{
		return $this->morphMany(Notification::class, 'notifiable');
	}
}
