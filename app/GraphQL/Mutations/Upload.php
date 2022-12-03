<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Package;
use App\Models\Session;
use App\Support\PackageHandler;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Kra8\Snowflake\Snowflake;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Upload
{
    /**
     */
    public function __invoke($_, array $args, GraphQLContext $context)
    {
        return DB::transaction(function () use ($args) {
            $session = Session::query()->create([
                'id' => app(Snowflake::class)->short(),
            ]);

            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $args['upload'];

            $packages = PackageHandler::make($file)->handle();

            Package::query()->upsert(
                values: $packages->toArray(),
                uniqueBy: ['name', 'url'],
            );

            $cookie = cookie()->make(
                name: config('app.cookie_name'),
                value: $session->getKey(),
                minutes: 0,
            );

            Cookie::queue($cookie);

            return Package::query()
                ->whereIn('name', $packages->pluck('name'))
                ->orderBy('name')
                ->get();
        });
    }
}
