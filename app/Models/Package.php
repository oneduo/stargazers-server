<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property string $name
 * @property string $url
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\PackageStargazer $pivot
 */
class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
    ];

    public function stargazers(): BelongsToMany
    {
        return $this->belongsToMany(Stargazer::class)
            ->using(PackageStargazer::class)
            ->withPivot(['starred_at']);
    }

    public function slug(): Attribute
    {
        return Attribute::get(fn () => Str::of($this->url)->after('https://github.com/'));
    }
}
