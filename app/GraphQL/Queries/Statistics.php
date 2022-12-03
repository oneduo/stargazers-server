<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Package;
use App\Models\PackageSession;
use App\Models\Stargazer;
use Illuminate\Support\Facades\Cache;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Statistics
{
    public function __invoke($_, array $args, GraphQLContext $context): array
    {
        return Cache::remember('statistics', config('app.stats_cache', 60), function () {

            return [
                'projectsCount' => Package::query()->count(),
                // on laisse comme ça au début hein :P
                'starsCount' => PackageSession::query()->count(),
                'usersCount' => Stargazer::query()->count(),
            ];
        });
    }
}
