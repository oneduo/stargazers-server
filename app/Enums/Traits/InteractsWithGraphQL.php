<?php

declare(strict_types=1);

namespace App\Enums\Traits;

use BackedEnum;
use GraphQL\Type\Definition\EnumType;
use GraphQL\Type\Definition\Type;
use Nuwave\Lighthouse\Schema\TypeRegistry;

trait InteractsWithGraphQL
{
    public static function graphQLName(): string
    {
        return str(static::class)->classBasename()->toString();
    }

    public static function graphQLType(): Type
    {
        return new EnumType([
            'name' => static::graphQLName(),
            'values' => collect(static::cases())->mapWithKeys(function (BackedEnum $enum) {
                return [
                    $enum->name => ['value' => $enum],
                ];
            })->toArray(),
        ]);
    }

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\DefinitionException
     */
    public static function registerGraphQLType(TypeRegistry $registry): void
    {
        $registry->register(static::graphQLType());
    }
}