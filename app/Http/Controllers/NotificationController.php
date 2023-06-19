<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(): Collection
    {
        $authenticatedUser = Auth::user();

        $notifications = Notification::whereHas('quote.user', function ($query) use ($authenticatedUser) {
            $query->where('id', $authenticatedUser->id);
        })->orderByDesc('created_at')->get();

        return $notifications;
    }

    public function markAsSeen($notificationId): Notification
    {

        $notification = Notification::find($notificationId);

        if ($notification && !$notification->seen) {
            $notification->update(['seen' => true]);
        }

        return $notification;

    }
}
