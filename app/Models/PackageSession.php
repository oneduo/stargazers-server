<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @property int $package_id
 * @property int $session_id
 * @property \Carbon\Carbon $starred_at
 * @property boolean $done
 * @property \App\Enums\Status $status
 */
class PackageSession extends Pivot
{
    public $timestamps = false;

    protected $fillable = [
        'starred_at',
        'package_id',
        'session_id',
        'status',
    ];

    protected $casts = [
        'starred_at' => 'datetime',
        'status' => Status::class,
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(Session::class);
    }
}
