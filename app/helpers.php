<?php

declare(strict_types=1);

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

if (!function_exists('classesIn')) {
    function classesIn(string $directory, ?string $namespace = null, ?string $basePath = null): Collection
    {
        $namespace ??= app()->getNamespace();
        $basePath ??= app_path();

        $classes = [];

        foreach ((new Finder)->in($directory)->files() as $class) {
            $class = $namespace . str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($class->getPathname(), rtrim($basePath, '/') . DIRECTORY_SEPARATOR)
                );

            if (!(new ReflectionClass($class))->isAbstract()) {
                $classes[] = $class;
            }
        }

        return collect($classes);
    }
}