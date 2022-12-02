<?php

declare(strict_types=1);

namespace App\GraphQL\Validators;

use Nuwave\Lighthouse\Validation\Validator;

class UploadValidator extends Validator
{
    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:json'],
        ];
    }
}
