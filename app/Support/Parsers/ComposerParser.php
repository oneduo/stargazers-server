<?php

declare(strict_types=1);

namespace App\Support\Parsers;

use Illuminate\Support\Collection;

class ComposerParser extends Parser
{
    public function parse(): Collection
    {
        $require = collect(data_get($this->data, 'packages', []))->map(fn (array $packages) => $this->map($packages))->filter();
        $requireDev = collect(data_get($this->data, 'packages-dev', []))->map(fn (array $packages) => $this->map($packages))->filter();

        return $require->merge($requireDev)->sortBy('name');
    }

    public function map(array $package): ?array
    {
        $url = data_get($package, 'source.url');

        if (blank($url) || ! str_starts_with($url, 'https://github.com/')) {
            return null;
        }

        return [
            'name' => data_get($package, 'name'),
            'url' => str($url)->before('.git')->toString(),
        ];
    }
}
