<?php

declare(strict_types=1);

namespace App\Support\Guessers;

use App\Contracts\GuessesFile;
use Illuminate\Http\UploadedFile;

abstract class Guesser implements GuessesFile
{
    public array $data = [];

    public function __construct(public UploadedFile $file)
    {
    }
}
