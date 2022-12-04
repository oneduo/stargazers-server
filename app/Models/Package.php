<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PackageType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property string $name
 * @property string $url
 * @property string $slug
 * @property string $image
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \App\Models\PackageSession $pivot
 * @property \App\Enums\PackageType $type
 */
class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'type',
    ];

    protected $appends = [
        'image',
        'slug',
    ];

    protected $casts = [
        'type' => PackageType::class,
    ];

    public function sessions(): BelongsToMany
    {
        return $this->belongsToMany(Session::class)
            ->using(PackageSession::class)
            ->withPivot(['starred_at', 'status']);
    }

    public function slug(): Attribute
    {
        return Attribute::get(function () {
            return str(parse_url($this->url, PHP_URL_PATH))
                ->ltrim('/')
                ->replace('.git', '')
                ->toString();
        });
    }

    public function image(): Attribute
    {
        return Attribute::get(function () {
            return str($this->slug)
                ->before('/')
                ->replace('@', '')
                ->wrap('https://github.com/', '.png')
                ->toString();
        });
    }
}
