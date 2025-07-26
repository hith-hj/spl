<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\AccountStatus;
use App\Enums\CodesTypes;
use App\Http\Resources\PartnerResource;
use App\Models\Partner;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

final class PartnerAuthServices
{
    public function __construct()
    {
        $this->setGuard();
    }

    public function create(Validator $validator): Partner
    {
        $partner = Partner::create([
            'username' => $this->username(explode('@', $validator->safe()->input('email'))[0]),
            'type' => $validator->safe()->input('type'),
            'email' => $validator->safe()->input('email'),
            'phone' => $validator->safe()->input('phone'),
            'fb_token' => $validator->safe()->input('fb_token'),
            'password' => bcrypt($validator->safe()->input('password')),
            'status' => AccountStatus::fresh->value,
            'is_active' => false,
            'is_visible' => false,
        ]);
        $partner->fresh()->verify($validator->safe()->input('by') ?? 'fcm');

        return $partner;
    }

    public function verify(Validator $validator): Partner
    {
        $partner = $this->getPartner($validator);
        $code = $partner->code(CodesTypes::verification->name);
        if ($code === null || $code->code !== $validator->safe()->integer('code')) {
            throw new Exception(__('main.invalid code'));
        }

        if ($code->expire_at !== null && $code->expire_at->lt(now())) {
            throw new Exception(__('main.code expired'));
        }
        $partner->verified();

        return $partner;
    }

    public function login(Validator $validator): array
    {
        $credentials = $validator->safe()->only('phone', 'password');
        if (! Auth::attempt($credentials)) {
            throw new Exception(__('main.invalid credentials'));
        }

        $partner = Auth::user();
        if (! Hash::check($validator->safe()->input('password'), $partner->password)) {
            throw new Exception(__('main.incorrect password'));
        }

        if ($partner->verified_at === null) {
            throw new Exception(__('main.unverified account'));
        }

        return [PartnerResource::make($partner), JWTAuth::fromUser($partner)];
    }

    public function forgetPassword(Validator $validator): Partner
    {
        $partner = $this->getPartner($validator);

        // if there is a previous code from registeration or other what to do
        $partner->verify();

        return $partner;
    }

    public function resetPassword(Validator $validator): Partner
    {
        $partner = $this->getPartner($validator);

        if (is_null($partner->verified_at)) {
            throw new Exception(__('main.verify account'), code: 401);
        }

        $partner->update(['password' => Hash::make($validator->safe()->input('password'))]);

        return $partner;
    }

    public function resendCode(Validator $validator): Partner
    {
        $partner = $this->getPartner($validator);

        if ($partner->verified_at !== null) {
            throw new Exception(__('main.invalid operation'), code: 403);
        }

        $partner->verify();

        return $partner;
    }

    public function changePassword(Validator $validator): Partner
    {
        $partner = Auth::user();
        if (! Hash::check($validator->safe()->input('old_password'), $partner->password)) {
            throw new Exception(__('main.invalid password'));
        }

        if ($partner->password === Hash::make($validator->safe()->input('new_password'))) {
            throw new Exception(__('main.passwords are equals'));
        }

        $partner->update(['password' => Hash::make($validator->safe()->input('new_password'))]);

        return $partner;
    }

    public function refreshToken()
    {
        return Auth::refresh();
    }

    public function logout()
    {
        return JWTAuth::invalidate(JWTAuth::getToken());
    }

    private function setGuard()
    {
        $guard = null;
        if (request()->is('*api/partner/*')) {
            $guard = 'partner:api';
        } elseif (request()->is('*api/customer/*')) {
            $guard = 'customer:api';
        } else {
            throw new Exception('Invalid route guard', 422);
        }
        config(['auth.defaults.guard' => $guard]);

    }

    private function getPartner($validator)
    {
        $partner = Partner::where('phone', $validator->safe()->input('phone'))->first();
        NotFound($partner);

        return $partner;
    }

    private function username($user): string
    {
        $username = $user.mt_rand(1000, 9999);

        $attemps = 0;
        while ($attemps < 5) {
            if (! Partner::where('username', $username)->exists()) {
                break;
            }
            $username = $user.mt_rand(1000, 9999);
        }

        return mb_strtolower($username);
    }
}
