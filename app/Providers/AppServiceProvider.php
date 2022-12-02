<?php

namespace App\Providers;

use App\Contracts\ShouldRegisterInGraphQL;
use Illuminate\Support\ServiceProvider;
use Nuwave\Lighthouse\Schema\TypeRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }

    public function boot(TypeRegistry $registry)
    {
        $this->registerTypes($registry);
    }

    protected function registerTypes(TypeRegistry $registry): void
    {
        classesIn(app_path('Enums'))
            ->each(function (string $class) use ($registry) {
                if (is_subclass_of($class, ShouldRegisterInGraphQL::class)) {
                    $class::registerGraphQLType($registry);
                }
            });
    }
}
