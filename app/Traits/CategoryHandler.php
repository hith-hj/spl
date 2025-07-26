<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait CategoryHandler
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
