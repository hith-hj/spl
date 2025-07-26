<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Activity extends Model
{
    use HasFactory;

    protected function casts()
    {
        return [
            'is_active' => 'boolean',
            'is_trial' => 'boolean',
            'is_private' => 'boolean',
        ];
    }

    public function belongTo(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'belongTo_type', 'belongTo_id');
    }
}
