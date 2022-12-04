<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property ?int $stargazer_id
 * @property-read ?\App\Models\Stargazer $stargazer
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $processed_at
 */
class Session extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'stargazer_id',
        'processed_at',
    ];

    public function stargazer(): BelongsTo
    {
        return $this->belongsTo(Stargazer::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->using(PackageSession::class)
            ->withPivot(['starred_at', 'status']);
    }
}
