<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quotes extends Model
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

	public function comments(): HasMany
	{
		return $this->hasMany(Comments::class, 'quote_id', 'id');
	}

	public function likes(): HasMany
	{
		return $this->hasMany(Likes::class, 'quote_id', 'id');
	}
}
