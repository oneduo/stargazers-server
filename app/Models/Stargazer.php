<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $github_id
 * @property string $username
 */
class Stargazer extends Model
{
    use HasFactory;

    protected $fillable = [
        'github_id',
        'username',
    ];
}
