<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Frustrate
{
    public array $routes = [
        'wp-admin*',
        'index*',
        'wp-login*',
        'server*',
        '.env*',
        'admin*',
        'login*',
    ];

    public array $redirect = [
        'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
        'https://www.youtube.com/watch?v=RfiQYRn7fBg',
        'https://www.youtube.com/watch?v=Fkk9DI-8el4',
        'https://www.fbi.gov/wanted',
        'https://github.com/oneduo',
        'https://cat-bounce.com/',
        'https://www.omfgdogs.com/',
        'https://www.youtube.com/watch?v=XqZsoesa55w',
    ];

    public function handle(Request $request, Closure $next)
    {
        foreach ($this->routes as $route) {
            if ($request->is($route)) {
                return redirect($this->redirect[array_rand($this->redirect)]);
            }
        }

        return $next($request);
    }
}
