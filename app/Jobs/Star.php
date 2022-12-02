<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\SessionFinished;
use App\Models\Package;
use App\Models\Session;
use Illuminate\Bus\Batch;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

class Star implements ShouldQueue, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Session $session, public ?string $token = null)
    {
    }

    /**
     * @throws \Throwable
     */
    public function handle(): void
    {
        $jobs = [];

        $this->session
            ->packages()
            ->orderBy('name')
            ->chunk(config('app.package_chunk_size', 100), function (Collection $collection) use (&$jobs) {
                $collection->each(function (Package $package) use (&$jobs) {
                    $jobs[] = new StarPackage($this->session, $package, $this->token);
                });
            });

        $session = $this->session;

        Bus::batch($jobs)
            ->name("Starring {$this->session->name}")
            ->finally(function (Batch $batch) use ($session) {
                if ($batch->finished()) {
//                    event(new SessionFinished($session));
                }
            })
            ->dispatch();
    }
}
