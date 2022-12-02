<?php

declare(strict_types=1);

namespace App\Support;

use App\Support\Guessers\ComposerGuesser;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Exceptions\ValidationException;

class PackageHandler
{
    public array $guessers = [
        ComposerGuesser::class,
    ];

    public function __construct(public UploadedFile $file)
    {
    }

    public static function make(UploadedFile $file): static
    {
        return new static($file);
    }

    /**
     * @throws \Nuwave\Lighthouse\Exceptions\ValidationException
     */
    public function handle(): Collection
    {
        foreach ($this->guessers as $guesser) {
            /** @var \App\Contracts\GuessesFile $guesser */
            $guesser = new $guesser($this->file);

            if ($guesser->detect()) {
                $guesser->validate();

                return $guesser->parser()->parse();
            }
        }

        throw ValidationException::withMessages([
            'file' => 'unsupported file type',
        ]);
    }
}
