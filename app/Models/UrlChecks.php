<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin Builder
 */
class UrlChecks extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $fillable = ['url_id', 'status_code',  'h1', 'keywords', 'description'];

    /**
     * @return BelongsTo
     */
    public function domain(): BelongsTo
    {
        return $this->belongsTo(__NAMESPACE__ . '\Url.php');
    }
}
