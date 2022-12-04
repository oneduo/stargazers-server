<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\ShouldRegisterInGraphQL;
use App\Enums\Traits\InteractsWithGraphQL;

enum PackageType: string implements ShouldRegisterInGraphQL
{
    use InteractsWithGraphQL;

    case PHP = 'php';
    case NPM = 'npm';
    case OTHER = 'other';
}
