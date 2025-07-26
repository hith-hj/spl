<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

final class ActivityValidators
{
    public static function find($data)
    {
        return Validator::make($data, [
            'activity_id' => ['required', 'exists:activities,id'],
        ]);
    }

    public static function create(array $data, bool $update = false)
    {
        $validator = Validator::make($data, [
            'name' => ['required', 'string'],
            'description' => ['required', 'string', 'max:500'],
            'type' => ['required', 'string', 'in:vip,normal'],
            'cost' => ['required', 'numeric', 'min:1'],
            'cancellation_cost' => ['required', 'numeric', 'min:1'],
            'is_trial' => ['required', 'boolean'],
            'is_private' => ['required', 'boolean'],
        ]);

        $validator->sometimes('activity_id', ['required', 'exists:activities,id'], function () use ($update) {
            return $update;
        });

        $validator->sometimes('trial_option', ['required', 'in:free,discount'], function ($input) {
            return $input->is_trial === true;
        });

        $validator->sometimes('discount_amount', ['required', 'numeric', 'min:1'], function ($input) {
            return $input->is_trial === true && $input->trial_option === 'discount';
        });

        $validator->sometimes('private_cost', ['required', 'numeric', 'min:1'], function ($input) {
            return $input->is_private === true;
        });

        return $validator;
    }

    public static function delete($data)
    {
        return Validator::make($data, [
            'activity_id' => ['required', 'exists:activities,id'],
        ]);
    }
}
