<?php

use Illuminate\Support\Facades\Route;
use Nuwave\Lighthouse\Execution\Utils\Subscription;

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

Route::get('/', function () {
    ray()->clearAll()->showQueries();
    /** @var \App\Models\Stargazer $stargazer */
    $stargazer = \App\Models\Stargazer::query()->with('packages')->find(473855293080872);

    $package = $stargazer->packages->first();

    Subscription::broadcast('packageUpdated', $package);

    return dd('done');
});
