<?php

declare(strict_types=1);

namespace App\Enums;

use App\Contracts\ShouldRegisterInGraphQL;
use App\Enums\Traits\InteractsWithGraphQL;

enum Status: string implements ShouldRegisterInGraphQL
{
    use InteractsWithGraphQL;

    case DONE = 'done';
    case PENDING = 'pending';
    case ERROR = 'error';
}

