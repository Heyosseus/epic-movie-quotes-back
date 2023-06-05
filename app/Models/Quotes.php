<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotes extends Model
{
	use HasFactory;

	protected $guarded = ['id'];

	public function quotes(): BelongsTo
	{
		return $this->belongsTo(Movie::class);
	}
}
