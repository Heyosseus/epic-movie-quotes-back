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
			'quote_id' => 1,
			'comment'  => $type === 'comment' ? 'commented on your quote' : null,
			'like'     => $type === 'like' ? 'liked your quote' : null,
		];

		event(new NotificationReceived($notification));
		$this->saveNotification($notification);

		return response()->json(['message' => 'success'], 200);
	}

	private function saveNotification($notification): void
	{
		Notification::create([
			'to'       => $notification->to,
			'from'     => $notification->from,
			'user_id'  => auth('sanctum')->user()->id,
			'quote_id' => $notification->quote_id,
			'like'     => $notification->like,
			'comment'  => $notification->comment,
		]);
	}

	public function index(): JsonResponse
	{
		$user = auth('sanctum')->user();
		$notifications = Notification::with('quotes', 'user')
			->where('to', $user->id)
			->where('from', '!=', $user->name)
			->orderBy('created_at', 'desc')
			->get();

		return response()->json($notifications);
	}

	public function getFilteredNotifications($userId): JsonResponse
	{
		$notifications = Notification::where('user_id', $userId)
			->where('from', '!=', auth()->user()->name)
			->get();

		return response()->json($notifications);
	}

	public function markAsRead(Notification $notification): JsonResponse
	{
		$notification->update(['read' => true]);

		return response()->json(['message' => 'Notification marked as read'], 200);
	}
}
