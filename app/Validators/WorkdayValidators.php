<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

final class WorkdayValidators
{
    public static function find($data)
    {
        return Validator::make($data, [
            'workday_id' => ['required', 'exists:workdays,id'],
        ]);
    }

    public static function create($data)
    {
        return Validator::make($data, [
            'day' => ['required', 'string', 'date_format:l'],
            'from' => ['required', 'date_format:H'],
            'to' => ['required', 'date_format:H'],
            'slot_duration_id' => ['required', 'exists:slots_durations,id'],
        ]);
    }

    public static function update($data)
    {
        return Validator::make($data, [
            'workday_id' => ['required', 'exists:workdays,id'],
            'from' => ['required', 'date_format:H'],
            'to' => ['required', 'date_format:H'],
            'slot_duration_id' => ['required', 'exists:slots_durations,id'],
        ]);
    }

    public static function delete($data)
    {
        return Validator::make($data, [
            'workday_id' => ['required', 'exists:workdays,id'],
        ]);
    }
}
