<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Movie extends Model
{
	use HasFactory , HasTranslations;

	protected $guarded = ['id'];

	protected $casts = [
		'title' => 'array',
	];

	public array $translatable = ['title', 'description', 'director', 'genres'];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function quotes(): hasMany
	{
		return $this->hasMany(Quote::class, 'movie_id');
	}

	public function genres(): BelongsToMany
	{
		return $this->belongsToMany(Genre::class, 'genre_movie', 'movie_id', 'genre_id')->withTimestamps();
	}
}
