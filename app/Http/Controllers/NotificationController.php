<?php

namespace App\Http\Controllers;

use App\Events\NotificationReceived;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function notify(User $user): JsonResponse
	{
		$notifications = (object)[
			'to'   => $user->id,
			'from' => auth('sanctum')->user()->name,
		];

		event(new NotificationReceived($notifications));

		return response()->json(['message' => 'success'], 200);
	}
}
