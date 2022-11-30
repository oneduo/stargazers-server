<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Composer
{
    public function __construct(protected array|string $json) {}

    public static function make(array|string $json): static
    {
        return new static($json);
    }

    public function packages(): Collection
    {
        $mapCallback = function (array $package) {
            $url = $package['source']['url'] ?? null;

            if (empty($url) || !str_starts_with($url, 'https://github.com/')) {
                return null;
            }

            return [
                'name' => $package['name'],
                'url' => Str::of($url)->before('.git'),
            ];
        };

        $require = collect($this->data()['packages'] ?? [])->map($mapCallback)->filter();
        $requireDev = collect($this->data()['packages-dev'] ?? [])->map($mapCallback)->filter();

        return $require->merge($requireDev);
    }

    protected function data(): array
    {
        return once(function () {
            return is_array($this->json)
                ? $this->json
                : json_decode($this->json, true, JSON_THROW_ON_ERROR);
        });
    }
}
