<?php

namespace App\Http\Controllers;

use App\Events\NotificationReceived;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function notify(User $user, $type, Request $request): JsonResponse
	{
		$quoteId = $request->input('quote_id');

		$notification = (object) [
			'to'       => $user->id,
			'from'     => auth('sanctum')->user()->name,
			'quote_id' => $quoteId,
			'type'     => $type,
		];

		event(new NotificationReceived($notification));
		$this->saveNotification($notification);
		$notification = Notification::with('quotes', 'quotes.movie')->latest()->first();

		return response()->json(['message' => 'success', 'notification'=> $notification], 200);
	}

	private function saveNotification($notification): void
	{
		Notification::create([
			'to'       => $notification->to,
			'from'     => $notification->from,
			'user_id'  => auth('sanctum')->user()->id,
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
			->orderBy('created_at', 'desc')
			->get();

		return response()->json($notifications);
	}

		public function markAsRead(Notification $notification): JsonResponse
		{
			$notification->update(['read' => true]);

			return response()->json(['message' => 'Notification marked as read'], 200);
		}

		public function markAllAsRead(): JsonResponse
		{
			Notification::query()->update(['read' => true]);
			return response()->json(['message' => 'Notifications marked as read'], 200);
		}
}
