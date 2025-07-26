<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Workday;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait WorkdayHandler
{
    public function workdays(): HasMany
    {
        return $this->hasMany(Workday::class, 'belongTo_id')
            ->withAttributes(['belongTo_type' => $this::class]);
    }
}
