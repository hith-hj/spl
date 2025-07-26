<?php

declare(strict_types=1);

namespace App\Validators;

use App\Enums\PartnersTypes;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class PartnerAuthValidators
{
    public static function register(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:partners'],
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'unique:partners'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', Rule::in(PartnersTypes::cases())],
            'fb_token' => ['required'],
        ]);
    }

    public static function verify(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'exists:partners'],
            'code' => ['required', 'numeric', 'exists:codes,code'],
        ]);
    }

    public static function login(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'exists:partners'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    public static function forgetPassword(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'exists:partners'],
        ]);
    }

    public static function resetPassword(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'exists:partners'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    public static function resendCode(array $data)
    {
        return Validator::make($data, [
            'phone' => ['required', 'regex:/^09[1-9]{1}\d{7}$/', 'exists:partners'],
        ]);
    }

    public static function changePAssword(array $data)
    {
        return Validator::make($data, [
            'old_password' => ['required', 'string', 'min:8'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }
}
