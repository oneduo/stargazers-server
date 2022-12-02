<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Stargazer;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Packages
{
    /**
     * @throws \Exception
     */
    public function __invoke($_, array $args, GraphQLContext $context): Collection
    {
        if (! $id = $context->request()->cookie(config('app.cookie_name'))) {
            throw ValidationException::withMessages([
                'stargazers_process_id' => 'Unknown process',
            ]);
        }

        /** @var \App\Models\Stargazer $stargazer */
        if (! $stargazer = Stargazer::query()->find($id)) {
            throw ValidationException::withMessages([
                'stargazers_process_id' => 'Unknown session',
            ]);
        }

        return $stargazer->packages()
            ->orderBy('name')
            ->get();
    }
}
