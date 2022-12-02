<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\PackageSession;
use App\Models\Session;
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
                'session' => 'Unknown process',
            ]);
        }

        /** @var \App\Models\Session $session */
        if (!$session = Session::query()->find($id)) {
            throw ValidationException::withMessages([
                'session' => 'Unknown session',
            ]);
        }

        return DB::transaction(function () use ($session, $args) {
            $insert = collect(data_get($args, 'packages'))
                ->map(fn(int $id) => [
                    'package_id' => $id,
                    'session_id' => $session->getKey(),
                ]);

            PackageSession::query()->insert($insert->toArray());

            return Socialite::driver('github')
                ->scopes(['read:user', 'public_repo'])
                ->stateless()
                ->with(['state' => $session->getKey()])
                ->redirect()
                ->getTargetUrl();
        });
    }
}
