<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Events\SessionFinished;
use App\Models\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FinishSession implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Session $session)
    {
    }

    public function handle()
    {
        $this->session->update(['done' => true, 'token' => null]);

        event(new SessionFinished($this->session->getKey()));
    }
}