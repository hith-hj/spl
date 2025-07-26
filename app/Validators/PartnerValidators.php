<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

final class PartnerValidators
{
    public static function stadium(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'owner' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
    }

    public static function trainer(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:20'],
            'last_name' => ['required', 'string', 'max:20'],
            'birth_date' => ['required', 'date_format:Y-m-d'],
            'gender' => ['required', 'string', 'in:male,female'],
            'type' => ['required', 'string', 'exists:trainers_types,name'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);
    }

    public static function update(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'min:5', 'max:25', 'unique:partners,username'],
            'description' => ['required', 'min:5', 'max:500'],
        ]);
    }
}
