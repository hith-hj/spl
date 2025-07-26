<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Course;
use App\Models\Court;
use Exception;

trait CourseHandler
{
    public function courses()
    {
        if ($this::class === Court::class) {
            return $this->hasMany(Course::class)
                ->withAttributes(['in_public' => true]);
        }
        throw new Exception('Invalid class for lessons');
    }
}
