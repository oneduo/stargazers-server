<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $name
 * @property string $url
 * @property string $slug
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\PackageSession $pivot
 */
class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
    ];

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class)
            ->using(PackageSession::class)
            ->withPivot(['starred_at', 'status']);
    }

    public function slug(): Attribute
    {
        return Attribute::get(fn() => str($this->url)->after('https://github.com/')->toString());
    }
}
