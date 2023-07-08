<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Quote extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function movie(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id', 'id');
	}

	public function likes()
	{
		return $this->belongsToMany(User::class, 'quote_user', 'quote_id', 'user_id')
			->withPivot('likes')
			->withTimestamps();
	}

	public function comments(): HasMany
	{
		return $this->hasMany(Comment::class, 'quote_id', 'id');
	}

	public function notifications(): MorphMany
	{
		return $this->morphMany(Notification::class, 'notifiable');
	}
}
