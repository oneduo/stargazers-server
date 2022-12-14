<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\Star;
use App\Models\Session;
use App\Models\Stargazer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Facades\Socialite;

class OauthController
{
    public function __invoke(Request $request)
    {
        $state = $request->input('state');

        /** @var \App\Models\Session $session */
        if (! $session = Session::query()->find($state)) {
            return redirect(config('app.front_url'));
        }

        return DB::transaction(function () use ($session) {
            $user = Socialite::driver('github')->stateless()->user();

            /** @var \App\Models\Stargazer $stargazer */
            $stargazer = Stargazer::query()->updateOrCreate([
                'github_id' => $user->id,
            ], [
                'username' => $user->nickname,
            ]);

            $session->update([
                'stargazer_id' => $stargazer->getKey(),
            ]);

            Star::dispatch($session, $user->token);

            return redirect(config('app.front_url').'/star/'.$session->getKey());
        });
    }
}
