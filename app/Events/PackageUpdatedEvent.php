<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Package;
use App\Models\PackageStargazer;
use App\Models\Stargazer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PackageUpdatedEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Stargazer $stargazer, public Package $package, public ?PackageStargazer $pivot = null)
    {
    }

    public function broadcastWith(): array
    {
        return [
            'package' => array_merge($this->package->toArray(), [
                'pivot' => [
                    'starred_at' => $this->pivot?->starred_at?->toIso8601String(),
                ],
            ]),
        ];
    }

    public function broadcastAs(): string
    {
        return 'package.updated';
    }

    public function broadcastOn(): Channel
    {
        return new Channel('session.'.$this->stargazer->id);
    }
}
