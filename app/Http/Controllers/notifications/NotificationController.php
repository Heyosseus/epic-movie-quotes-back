<?php

namespace App\Http\Controllers\notifications;

use App\Events\NotificationReceived;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function notify(User $user, $type, Request $request): JsonResponse
	{
		$quoteId = $request->input('quote_id');
		$from = auth('sanctum')->user()->name;

		if ($user->id !== auth('sanctum')->id()) {
			$notification = (object) [
				'to'         => $user->id,
				'from'       => $from,
				'quote_id'   => $quoteId,
				'type'       => $type,
				'created_at' => now(),
				'read'       => 0,
			];

			if (auth('sanctum')->user()->profile_picture !== null) {
				$notification->profile_picture = auth('sanctum')->user()->profile_picture;
			}

			event(new NotificationReceived($notification));
			$this->saveNotification($notification);
		}

		return response()->json(['message' => 'success'], 200);
	}

	private function saveNotification($notification): void
	{
		Notification::create([
			'to'       => $notification->to,
			'from'     => $notification->from,
			'user_id'  => auth('sanctum')->id(),
			'quote_id' => $notification->quote_id,
			'type'     => $notification->type,
		]);
	}

	public function index(): JsonResponse
	{
		$user = auth('sanctum')->user();
		$notifications = Notification::with('quotes', 'user', 'user.quotes', 'quotes.comments.user', 'quotes.movie')
			->where('to', $user->id)
			->where('from', '!=', $user->name)
			->latest()
			->get();

		return response()->json($notifications);
	}

		public function markAsRead(Notification $notification): JsonResponse
		{
			$notification->update(['read' => 1]);

			return response()->json(['message' => 'Notification marked as read', $notification], 200);
		}

		public function markAllAsRead(): JsonResponse
		{
			$notification = Notification::query()->update(['read' => 1]);
			return response()->json(['message' => 'Notifications marked as read', $notification], 200);
		}
}
