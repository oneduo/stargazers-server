<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $package_id
 * @property int $stargazer_id
 * @property \Carbon\Carbon $starred_at
 */
class PackageStargazer extends Pivot
{
    protected $fillable = [
        'starred_at',
    ];

    protected $casts = [
        'starred_at' => 'datetime',
    ];
}
