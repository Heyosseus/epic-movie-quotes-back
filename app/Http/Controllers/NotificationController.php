<?php

namespace App\Http\Controllers;

use App\Events\NotificationReceived;
use App\Models\Notification;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
	public function notify(User $user, $type, Quote $quote, Request $request): JsonResponse
	{
		$notificationData = [
			'notifiable_type' => Quote::class,
			'notifiable_id'   => $quote->id,
			'type'            => $type,
			'from'            => auth('sanctum')->user()->name,
			'to'              => $user->name,
		];

		$userNotificationData = [
			'notifiable_type' => User::class,
			'notifiable_id'   => $user->id,
			'type'            => $type,
			'from'            => auth('sanctum')->user()->name,
			'to'              => $user->name,
		];

		$notification = (object) [
			'to'   => $user->id,
			'from' => auth('sanctum')->user()->name,
			'type' => $type,
		];

		event(new NotificationReceived($notification));

		$this->saveNotification($notificationData);
		$this->saveNotification($userNotificationData);

		//		$notifications = Notification::with('notifiable', 'notifiable.user', 'notifiable.comments')->get();

		return response()->json(['message' => 'success'], 200);
	}

	private function saveNotification($notificationData): void
	{
		$notifiableType = $notificationData['notifiable_type'];
		$notifiableId = $notificationData['notifiable_id'];

		unset($notificationData['notifiable_type'], $notificationData['notifiable_id']);

		$notification = new Notification($notificationData);

		try {
			$notifiable = $notifiableType::findOrFail($notifiableId);
			$notifiable->notifications()->save($notification);
		} catch (\Exception $e) {
		}
	}

	public function index(): JsonResponse
	{
		$notifications = Notification::where(function ($query) {
			$query->where('notifiable_type', Quote::class)
				->orWhere('notifiable_type', User::class);
		})
			->where('from', '!=', auth()->user()->name)
			->with('notifiable', 'notifiable.comments', 'notifiable.comments.user')
			->orderBy('created_at', 'desc')
			->get();

		$notifications->each(function ($notification) {
			$notification->sender = $notification->notifiable->user;
		});
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
