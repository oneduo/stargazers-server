<?php

declare(strict_types=1);

namespace App\Support\Parsers;

use App\Enums\PackageType;
use Illuminate\Support\Collection;

class NpmParser extends Parser
{
    public function parse(): Collection
    {
        $require = collect(data_get($this->data, 'packages', []))
            ->map(fn(array $package, string $key) => $key ? $this->map($key, $package) : null)
            ->filter()
            ->values();

        return $require->sortBy('name');
    }

    public function map(string $name, array $package): ?array
    {
        $resolved = data_get($package, 'resolved');

        $url = str($resolved)->before('/-/')->replace('.git', '')->toString();

        if (blank($url) || !str_starts_with($url, 'https://registry.npmjs.org/')) {
            return null;
        }

        $name = str($name)->after('node_modules/')->toString();

        return [
            'name' => $name,
            'url' => $url,
            'type' => PackageType::NPM,
        ];
    }
}
