<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

final class CourtValidators
{
    public static function find($data)
    {
        return Validator::make($data, [
            'court_id' => ['required', 'exists:courts,id'],
        ]);
    }

    public static function create($data)
    {
        return Validator::make($data, [
            'is_outdoor' => ['required', 'boolean'],
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'type' => ['required', 'exists:courts_types,name'],
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'unique:courts,phone'],
            'description' => ['nullable', 'max:500'],
        ]);
    }

    public static function update($data)
    {
        return Validator::make($data, [
            'court_id' => ['required', 'exists:courts,id'],
            'name' => ['required', 'string', 'min:4', 'max:100'],
            'description' => ['sometimes', 'string', 'max:500'],
        ]);
    }

    public static function delete($data)
    {
        return Validator::make($data, [
            'court_id' => ['required', 'exists:courts,id'],
        ]);
    }
}
