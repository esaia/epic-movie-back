<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LikeNotificationEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	public $quoteUserId;

	public function __construct($quoteUserId)
	{
		return $this->quoteUserId = $quoteUserId;
	}

	public function broadcastOn(): array
	{
		return [
			new Channel('likes'),
		];
	}
}
