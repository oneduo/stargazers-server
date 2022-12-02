<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Package;
use App\Models\Stargazer;
use App\Support\Composer;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Kra8\Snowflake\Snowflake;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Upload
{
    /**
     * @throws \JsonException
     */
    public function __invoke($_, array $args, GraphQLContext $context)
    {
        return DB::transaction(function () use ($args) {
            $stargazer = Stargazer::query()->create([
                'id' => app(Snowflake::class)->short(),
            ]);

            /** @var \Illuminate\Http\UploadedFile $file */
            $file = $args['upload'];

            $packages = Composer::make(json: $file->get())->packages();

            Package::query()->upsert(
                values: $packages->toArray(),
                uniqueBy: ['name', 'url'],
            );

            $cookie = cookie()->make(
                name: config('app.cookie_name'),
                value: $stargazer->getKey(),
                minutes: 0,
                path: '/',
                domain: '.' . parse_url(config('app.front_url'), PHP_URL_HOST),
            );

            Cookie::queue($cookie);

            return Package::query()
                ->whereIn('name', $packages->pluck('name'))
                ->orderBy('name')
                ->get();
        });
    }
}
