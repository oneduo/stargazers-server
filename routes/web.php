<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('test', function () {
    \Illuminate\Support\Facades\DB::transaction(function () {
        $s = \App\Models\Stargazer::query()->whereHas('packages')->with('packages')->first();

        /** @var \App\Models\Package $p */
        $p = $s->packages->first();

        $p->pivot->update(['starred_at' => now()]);
        dd($p->pivot->toArray());
    });
});
