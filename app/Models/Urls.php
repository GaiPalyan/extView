<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Urls extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function checks()
    {
        return $this->hasMany(__NAMESPACE__ . '\UrlChecks.php');
    }
}
