<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Stargazer;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Star
{
    /**
     * @throws \Exception
     */
    public function __invoke($_, array $args, GraphQLContext $context)
    {
        /** @var \App\Models\Stargazer $stargazer */
        if (! $stargazer = Stargazer::query()->find($args['stargazer'])) {
            throw new \Exception('Invalid request');
        }

        return DB::transaction(function () use ($stargazer, $args) {
            $stargazer->packages()->sync($args['packages']);

            return Socialite::driver('github')
                ->scopes(['read:user', 'public_repo'])
                ->stateless()
                ->with(['state' => $stargazer->getKey()])
                ->redirect()
                ->getTargetUrl();
        });
    }
}
