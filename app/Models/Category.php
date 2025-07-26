<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\MediaHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Category extends Model
{
    use HasFactory;
    use MediaHandler;

    public function partners()
    {
        return $this->belongsToMany(Partner::class);
    }
}
