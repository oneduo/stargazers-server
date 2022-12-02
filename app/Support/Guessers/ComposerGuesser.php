<?php

declare(strict_types=1);

namespace App\Support\Guessers;

use App\Contracts\ParsesPackages;
use App\Support\Parsers\ComposerParser;
use Exception;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class ComposerGuesser extends Guesser
{
    public function detect(): bool
    {
        return $this->file->getClientOriginalName() === 'composer.lock';
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

            $keys = ['_readme', 'content-hash', 'packages'];

            if (count(array_intersect($keys, array_keys($decoded))) !== count($keys)) {
                throw new Exception('Invalid composer.lock file');
            }

            $this->data = $decoded;
        } catch (Exception $e) {
            report($e);

            throw ValidationException::withMessages([
                'file' => 'Invalid composer.lock file',
            ]);
        }
    }

    public function parser(): ParsesPackages
    {
        return new ComposerParser($this->data);
    }
}
