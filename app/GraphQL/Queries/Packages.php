<?php

declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Jobs\Star;
use App\Models\Session;
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
        $id = $context->request()->cookie(config('app.cookie_name')) ?? data_get($args, 'session');

        if (!$id) {
            throw ValidationException::withMessages([
                'session' => 'Unknown process',
            ]);
        }

        /** @var \App\Models\Session $session */
        if (!$session = Session::query()->find($id)) {
            throw ValidationException::withMessages([
                'session' => 'Unknown session',
            ]);
        }

        return $session->packages()
            ->orderBy('name')
            ->get();
    }
}
