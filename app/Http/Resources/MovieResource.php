<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
	public function toArray(Request $request): array
	{
		return [
			'id'           => $this->id,
			'title'        => $this->title,
			'poster'       => $this->poster,
			'release_date' => $this->release_date,
			'description'  => $this->description,
			'director'     => $this->director,
			'quotes'       => QuoteResource::collection($this->whenLoaded('quotes')),
			'genres'       => GenreResource::collection($this->whenLoaded('genres')),
			'user'         => new UserResource($this->whenLoaded('user')),
		];
	}
}
