<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class UrlChecks extends Model
{
    use HasFactory;

    protected $fillable = ['url_id', 'status_code',  'h1', 'keywords', 'description'];

    public function domain()
    {
        return $this->belongsTo(__NAMESPACE__ . '\Url.php');
    }
}
