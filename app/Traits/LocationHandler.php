<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Location;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait LocationHandler
{
    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'belongTo_id')
            ->withAttributes(['belongTo_type' => $this::class]);
    }
}
