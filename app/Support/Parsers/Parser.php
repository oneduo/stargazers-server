<?php

declare(strict_types=1);

namespace App\Support\Parsers;

use App\Contracts\ParsesPackages;

abstract class Parser implements ParsesPackages
{
    public function __construct(public array $data)
    {
    }
}
