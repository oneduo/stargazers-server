<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\Status;
use App\Events\StarProcessed;
use App\Models\Package;
use App\Models\PackageSession;
use App\Models\Session;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeEncrypted;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class StarPackage implements ShouldQueue, ShouldBeUnique, ShouldBeEncrypted
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use Batchable;

    public function __construct(public Session $session, public Package $package, public string $token)
    {
    }

    public function handle()
    {
        if ($this->batch()->cancelled()) {
            return;
        }

        /** @var PackageSession $pivot */
        $pivot = PackageSession::query()
            ->whereBelongsTo($this->package)
            ->whereBelongsTo($this->session)
            ->first();

        if ($pivot?->status === Status::DONE) {
            return;
        }

        if (str($this->package->url)->startsWith('https://registry.npmjs.org/') && !$this->handleNpm()) {
            return;
        }

        $name = str($this->package->slug)->lower()->toString();

        $response = Http::withHeaders([
            'Accept' => 'application/vnd.github+json',
            'Authorization' => "Bearer " . $this->token,
            'Content-Length' => 0,
        ])
            ->put("https://api.github.com/user/starred/{$name}");

        $this->session->packages()
            ->updateExistingPivot($this->package->id, [
                'status' => $response->successful() ? Status::DONE : Status::ERROR,
                'starred_at' => $response->successful() ? now() : null,
            ]);

        event(new StarProcessed($this->session, $this->package));
    }

    public function uniqueId(): string
    {
        return "job.sessions:{$this->session->getKey()}.package:{$this->package->getKey()}";
    }

    public function handleNpm(): bool
    {
        $registry = Http::get($this->package->url);

        $url = $registry->json('repository.url');

        if (!$url) {
            return false;
        }

        $this->package->update([
            'url' => str($url)
                ->replace('git+', '')
                ->replace('.git', '')
                ->toString(),
        ]);

        return true;
    }
}
