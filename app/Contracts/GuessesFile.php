<?php

declare(strict_types=1);

namespace App\Contracts;

interface GuessesFile
{
    public function detect(): bool;

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\ValidationException
     */
    public function validate(): void;

    public function parser(): ParsesPackages;
}
