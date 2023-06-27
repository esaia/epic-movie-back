<?php

namespace App\Http\Controllers;

use App\Events\LikeNotificationEvent;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Quote;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
	public function store(LikeRequest $request): Like
	{
		$attributes = $request->validated();

		$like = Like::create($attributes);

		$status = 'like';
		$notificatinAttributes = [
			'sender_id' => $like->user_id,
			'quote_id'  => $like->quote_id,
			'status'    => $status,
		];

		$quote = Quote::find($like->quote_id);

		if ($quote->user_id !== Auth::id()) {
			$notification = Notification::create($notificatinAttributes);
			event(new LikeNotificationEvent($notification->quote->user->id));
		}

		return $like;
	}

	public function destroy($id): JsonResponse
	{
		$like = Like::findOrFail($id);
		$like->delete();
		return Response()->json(['msg' => 'like deleted'], 200);
	}
}
