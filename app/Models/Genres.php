<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Genres extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	protected $casts = [
		'name' => 'array',
	];

	public function movie(): BelongsToMany
	{
		return $this->belongsToMany(Movie::class, 'genres_movie', 'genre_id', 'movie_id')->withTimestamps();
	}
}
