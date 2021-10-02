<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 */
class Urls extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    /**
     * @return HasMany
     */
    public function checks(): HasMany
    {
        return $this->hasMany(__NAMESPACE__ . '\UrlChecks.php');
    }

    public function scopeDomainsList($query)
    {
        return $query->select('id', 'name')->orderByDesc('created_at');
    }
}
