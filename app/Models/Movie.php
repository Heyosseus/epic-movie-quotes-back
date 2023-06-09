<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movie extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $casts = [
		'title' => 'array',
	];

	public function movie(): hasMany
	{
		return $this->hasMany(Quotes::class);
	}

	public function genre(): BelongsToMany
	{
		return $this->belongsToMany(Genres::class);
	}
}
