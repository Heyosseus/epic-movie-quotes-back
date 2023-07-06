<?php

namespace App\Http\Controllers;

use App\Events\NotificationReceived;
use App\Models\Notification;
use App\Models\Quotes;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function notify(User $user, $type, Request $request): JsonResponse
	{
		$quoteId = $request->input('quote_id');
		$quote = Quotes::find($quoteId);

		$notificationData = [
			'notifiable_type' => get_class($user),
			'notifiable_id'   => $user->id,
			'from'            => auth('sanctum')->user()->name,
			'to'              => $user->name,
		];

		$notification = $quote->notifications()->create($notificationData);

		event(new NotificationReceived($notification));
		return response()->json(['message' => 'success', $notification], 200);
	}

	public function index(): JsonResponse
	{
		$notifications = Notification::where('notifiable_type', Quotes::class)
			->where('from', '!=', auth()->user()->name)
			->with('notifiable')
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
