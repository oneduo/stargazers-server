<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Package;
use App\Models\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StarFailed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Session $session, public Package $package)
    {
    }

    public function broadcastWith(): array
    {
        return [
            'package' => $this->package,
        ];
    }

    public function broadcastAs(): string
    {
        return 'star.failed';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('session.' . $this->session->getKey());
    }

    public function broadcastQueue(): string
    {
        return 'pusher';
    }
}