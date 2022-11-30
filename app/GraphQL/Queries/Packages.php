<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Stargazer;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Packages
{
    /**
     * @throws \Exception
     */
    public function __invoke($_, array $args, GraphQLContext $context): Collection
    {
        /** @var \App\Models\Stargazer $stargazer */
        if (!$stargazer = Stargazer::query()->find($args['stargazer'])) {
            throw new \Exception('Invalid request');
        }

        return $stargazer->packages()
            ->orderBy('name')
            ->get();
    }
}
