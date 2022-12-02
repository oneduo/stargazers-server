<?php

declare(strict_types=1);

namespace App\Contracts;

use GraphQL\Type\Definition\Type;
use Nuwave\Lighthouse\Schema\TypeRegistry;

interface ShouldRegisterInGraphQL
{
    public static function graphQLName(): string;
    public static function graphQLType(): Type;
    public static function registerGraphQLType(TypeRegistry $registry): void;
}
