<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

final class CourseValidators
{
    public static function find($data)
    {
        return Validator::make($data, [
            'course_id' => ['required', 'exists:courses,id'],
        ]);
    }

    public static function create(array $data , bool $update = false)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'type' => ['required', 'in:daily,monthly'],
            'in_public' => ['required','boolean'],
            'description' => ['nullable', 'max:500'],
            'is_multiPerson' => ['required',],
            'cost' => ['required','numeric'],
            'cancellation_cost' => ['required','numeric'],
            'is_outdoor' => ['required','boolean'],
            'trainers' => ['nullable','array','max:5'],
            'trainers.*' => ['exists:partners,id',],
        ]);

        $validator->sometimes('course_id', ['required', 'exists:courses,id'], function () use ($update) {
            return $update;
        });

        $validator->sometimes('capacity', ['required', 'numeric', 'min:2','max:25'], function ($input) {
            return $input->is_multiPerson === true;
        });
        return $validator;
    }

    public static function delete($data)
    {
        return Validator::make($data, [
            'course_id' => ['required', 'exists:courses,id'],
        ]);
    }
}
