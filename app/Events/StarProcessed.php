<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Package;
use App\Models\PackageSession;
use App\Models\Session;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StarProcessed implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public function __construct(public Session $session, public Package $package)
    {
    }

    public function broadcastWith(): array
    {
        /** @var \App\Models\PackageSession $pivot */
        $pivot = PackageSession::query()
            ->whereBelongsTo($this->package)
            ->whereBelongsTo($this->session)
            ->first();

        return [
            'package' => array_merge($this->package->toArray(), [
                '__typename' => class_basename(Package::class),
                'pivot' => [
                    '__typename' => class_basename(PackageSession::class),
                    'starred_at' => $pivot->starred_at?->toIso8601String(),
                    'status' => $pivot->status->name,
                ],
            ]),
        ];
    }

    public function broadcastAs(): string
    {
        return 'star.processed';
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