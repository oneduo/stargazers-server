<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\Star;
use App\Models\Stargazer;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class OauthController
{
    public function __invoke(Request $request)
    {
        $state = $request->input('state');

        /** @var \App\Models\Stargazer $stargazer */
        if (! $stargazer = Stargazer::query()->find($state)) {
            return redirect(config('app.front_url'));
        }

        $user = Socialite::driver('github')->stateless()->user();

        $stargazer->update([
            'github_id' => $user->id,
        ]);

        Star::dispatch($stargazer, $user->token);

        return redirect(config('app.front_url').'/star/'.$stargazer->getKey());
    }
}
