<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function movies()
	{
		return $this->belongsToMany(Movie::class, 'genre_movie', 'genre_id', 'movie_id')->withTimestamps();
	}
}
