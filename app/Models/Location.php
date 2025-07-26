<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class Location extends Model
{
    use HasFactory;

    protected function casts()
    {
        return [
            'long' => 'float',
            'lat' => 'float',
        ];
    }

    public function belongTo(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'belongTo_type', 'belongTo_id');
    }
}
