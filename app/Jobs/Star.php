<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\PackageUpdatedEvent;
use App\Events\Processed;
use App\Models\Package;
use App\Models\Stargazer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\Pool;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Star implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        public Stargazer $stargazer,
        public string $token
    ) {
    }

    public function handle(): void
    {
        $this->stargazer
            ->packages()
            ->chunk(10, function (Collection $collection) {
                $responses = Http::pool(function (Pool $pool) use ($collection) {
                    return $collection
                        ->map(function (Package $package) use ($pool) {
                            if ($package->pivot?->starred_at !== null) {
                                return null;
                            }

                            return $pool->as($package->getKey())
                                ->withHeaders([
                                    'Accept' => 'application/vnd.github+json',
                                    'Authorization' => "Bearer {$this->token}",
                                    'Content-Length' => 0,
                                ])
                                ->put("https://api.github.com/user/starred/$package->slug");
                        })
                        ->filter();
                });

                $collection->each(function (Package $package) use ($responses) {
                    /** @var \Illuminate\Http\Client\Response $response */
                    if (! $response = $responses[$package->getKey()] ?? null) {
                        event(new PackageUpdatedEvent($this->stargazer, $package, $package->pivot));

                        return true;
                    }

                    if ($response->successful()) {
                        $package->pivot->update(['starred_at' => now()]);
                    }

                    event(new PackageUpdatedEvent($this->stargazer, $package, $package->pivot));

                    return true;
                });
            });

        event(new Processed($this->stargazer));
    }
}
