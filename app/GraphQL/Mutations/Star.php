<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Stargazer;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;
use Nuwave\Lighthouse\Exceptions\ValidationException;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Star
{
    /**
     * @throws \Exception
     */
    public function __invoke($_, array $args, GraphQLContext $context)
    {
        if (!$id = $context->request()->cookie(config('app.cookie_name'))) {
            throw ValidationException::withMessages([
                'stargazers_process_id' => 'Unknown process',
            ]);
        };


        /** @var \App\Models\Stargazer $stargazer */
        if (!$stargazer = Stargazer::query()->find($id)) {
            throw ValidationException::withMessages([
                'stargazers_process_id' => 'Unknown session',
            ]);
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
