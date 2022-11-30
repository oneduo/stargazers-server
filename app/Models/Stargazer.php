<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Stargazer extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $fillable = [
        'id',
        'github_id',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class)
            ->using(PackageStargazer::class)
            ->withPivot(['starred_at']);
    }
}
