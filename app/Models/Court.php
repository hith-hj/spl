<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\ActivityHandler;
use App\Traits\CourseHandler;
use App\Traits\Eraser;
use App\Traits\LocationHandler;
use App\Traits\MediaHandler;
use App\Traits\PartnerHandler;
use App\Traits\WorkdayHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Court extends Model
{
    use ActivityHandler;
    use CourseHandler;
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
        ];
    }
}
