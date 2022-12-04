<?php

declare(strict_types=1);

namespace App\Support\Guessers;

use App\Contracts\ParsesPackages;
use App\Support\Parsers\ComposerParser;
use App\Support\Parsers\NpmParser;
use Exception;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class NpmGuesser extends Guesser
{
    public function detect(): bool
    {
        return $this->file->getClientOriginalName() === 'package-lock.json';
    }

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\ValidationException
     */
    public function validate(): void
    {
        try {
            if (! $decoded = json_decode($this->file->get(), true, 512, JSON_THROW_ON_ERROR)) {
                throw new Exception('Invalid JSON');
            }

            $keys = ['lockfileVersion', 'packages'];

            if (count(array_intersect($keys, array_keys($decoded))) !== count($keys)) {
                throw new Exception('Invalid package-lock.json file');
            }

            $this->data = $decoded;
        } catch (Exception $e) {
            report($e);

            throw ValidationException::withMessages([
                'file' => 'Invalid package-lock.json file',
            ]);
        }
    }

    public function parser(): ParsesPackages
    {
        return new NpmParser($this->data);
    }
}
