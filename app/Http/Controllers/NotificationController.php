<?php

namespace App\Http\Controllers;

use App\Events\NotificationReceived;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
	public function notify(User $user, $type): JsonResponse
	{
		$notification = (object)[
			'to'      => $user->id,
			'from'    => auth('sanctum')->user()->name,
			'comment' => $type === 'comment' ? 'commented on your quote' : null,
			'like'    => $type === 'like' ? 'liked your quote' : null,
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
			'like'     => $notification->like,
			'comment'  => $notification->comment,
		]);
	}

	public function index(): JsonResponse
	{
		$user = auth('sanctum')->user();
		$notifications = Notification::where('to', $user->id)
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
