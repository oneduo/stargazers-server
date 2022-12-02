<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\Star;
use App\Models\Session;
use Illuminate\Http\Request;
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

        $user = Socialite::driver('github')->stateless()->user();

        $session->update([
            'github_id' => $user->id,
        ]);

        Star::dispatch($session, $user->token);

        return redirect(config('app.front_url').'/star/'.$session->getKey());
    }
}
