<?php

declare(strict_types=1);

use App\Http\Controllers\OauthController;
use App\Http\Controllers\OgController;
use App\Http\Middleware\AllowVercelEdge;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('oauth/callback', OauthController::class)->name('oauth.callback');
Route::get('session/{id}', OgController::class)->name('og.session')->middleware(AllowVercelEdge::class);