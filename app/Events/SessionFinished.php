<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SessionFinished implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Session $session)
    {
    }

    public function broadcastWith(): array
    {
        return [];
    }

    public function broadcastAs(): string
    {
        return 'session.finished';
    }

    public function broadcastOn(): Channel
    {
        return new Channel("session.{$this->session->getKey()}");
    }

    public function broadcastQueue(): string
    {
        return 'pusher';
    }
}
