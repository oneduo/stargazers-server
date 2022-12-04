<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Session;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class OgController extends Controller
{
    public function __invoke(string $id): JsonResponse
    {
        return response()->json(Cache::rememberForever("og.session.{$id}", fn() => Session::query()->with(['stargazer:username', 'packages'])->find($id)));
    }
}