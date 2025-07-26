<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait ActivityHandler
{
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'belongTo_id')
            ->withAttributes(['belongTo_type' => $this::class]);
    }
}
