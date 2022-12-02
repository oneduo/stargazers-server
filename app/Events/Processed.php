<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Stargazer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Processed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Stargazer $stargazer)
    {
    }

    public function broadcastWith(): array
    {
        return [];
    }

    public function broadcastAs(): string
    {
        return 'package.processed';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('session.'.$this->stargazer->id);
    }
}
