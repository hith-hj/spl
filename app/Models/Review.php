<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Review extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function casts()
    {
        return [
            'rate' => 'float',
        ];
    }

    public function belongTo(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'belongTo_type', 'belongTo_id');
    }

    public function reviewer(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'reviewer_type', 'reviewer_id');
    }
}
