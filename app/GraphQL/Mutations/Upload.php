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
            $stargazer = Stargazer::query()->updateOrCreate([
                'id' => app(Snowflake::class)->short(),
            ]);

            $packages = Composer::make(json: $args['upload']->get())->packages();

            Package::query()->upsert(
                values: $packages->toArray(),
                uniqueBy: ['name', 'url'],
            );

            Cookie::queue(
                name: 'stargazers_process_id',
                value: $stargazer->getKey(),
                minutes: 10,
            );

            return Package::query()
                ->whereIn('name', $packages->pluck('name'))
                ->orderBy('name')
                ->get();
        });
    }
}
