<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\SlotHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Workday extends Model
{
    use HasFactory;
    use SlotHandler;

    protected $with = ['slots'];

    protected function casts()
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function belongTo()
    {
        return $this->morphTo(__FUNCTION__, 'belongTo_type', 'belongTo_id');
    }
}
