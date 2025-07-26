<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\SlotHandler;
use Illuminate\Database\Eloquent\Model;

final class Slot extends Model
{
    use SlotHandler;

    protected $appends = ['duration'];

    protected $hidden = ['created_at', 'updated_at', 'belongTo_id', 'belongTo_type', 'duration_id'];

    public function belongTo()
    {
        return $this->morphTo(__FUNCTION__, 'belongTo_type', 'belongTo_id');
    }

    public function getDurationAttribute()
    {
        return $this->getSlotsDuration($this->duration_id);
    }
}
