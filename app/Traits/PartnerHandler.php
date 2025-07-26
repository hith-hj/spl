<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait PartnerHandler
{
    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function getFbTokenAttribute(): string
    {
        return $this->partner->fb_token;
    }
}
