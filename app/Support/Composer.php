<?php

declare(strict_types=1);

namespace App\Support;

use Exception;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class Composer
{
    public function __construct(protected array|string $json)
    {
    }

    public static function make(array|string $json): static
    {
        return new static($json);
    }

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\ValidationException
     */
    public function packages(): Collection|ValidationException
    {
        $data = $this->data();

        $require = collect(data_get($data, 'packages', []))->map(fn(array $packages) => $this->map($packages))->filter();
        $requireDev = collect(data_get($data, 'packages-dev', []))->map(fn(array $packages) => $this->map($packages))->filter();

        return $require->merge($requireDev);
    }

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\ValidationException
     */
    protected function data(): array|ValidationException
    {
        try {
            $data = json_decode($this->json, true, JSON_THROW_ON_ERROR);
        } catch (Exception $e) {
            report($e);

            $data = null;
        }

        if (blank($data)) {
            throw ValidationException::withMessages([
                'json' => 'Invalid JSON',
            ]);
        }

        return $data;
    }

    public function map(array $package): ?array
    {
        $url = data_get($package, 'source.url');

        if (blank($url) || !str_starts_with($url, 'https://github.com/')) {
            return null;
        }

        return [
            'name' => data_get($package, 'name'),
            'url' => str($url)->before('.git')->toString(),
        ];
    }
}
