<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ActivityHandler;
use App\Traits\LocationHandler;
use App\Traits\MediaHandler;
use App\Traits\PartnerHandler;
use App\Traits\WorkdayHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Course extends Model
{
    use ActivityHandler;
    use HasFactory;
    use LocationHandler;
    use MediaHandler;
    use PartnerHandler;
    use WorkdayHandler;

    // use Eraser;

    protected function casts()
    {
        return [
            'is_main' => 'boolean',
            'is_active' => 'boolean',
            'is_outdoor' => 'boolean',
            'in_public' => 'boolean',
            'is_private' => 'boolean',
            'is_multiPerson' => 'boolean'
        ];
    }

    public function trainer()
    {
        return $this->belongsTo(Partner::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class)->withAttributes(['in_public' => false]);
    }

    public function getIsFilledAttribute()
    {
        return $this->activities()->count() > 0 &&
            $this->workdays()->count() > 0 &&
            $this->location()->exists();
    }
}
