<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property int $github_id
 * @property boolean $done
 * @property ?string $token
 */
class Session extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'github_id',
        'done',
        'token',
    ];

    protected $casts = [
        'token' => 'encrypted',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->using(PackageSession::class)
            ->withPivot(['starred_at', 'status']);
    }
}
