<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Enums\CodesTypes;
use App\Http\Controllers\Controller;
use App\Services\PartnerAuthServices;
use App\Validators\PartnerAuthValidators;
use Illuminate\Http\Request;

final class AuthController extends Controller
{
    public function __construct(public PartnerAuthServices $services) {}

    public function register(Request $request)
    {
        $validator = PartnerAuthValidators::register($request->all());
        $partner = $this->services->create($validator);

        return Success(
            msg: __('main.registerd'),
            payload:['code'=>$partner->code(CodesTypes::verification->name)->code],
            code: 201
        );
    }

    public function verify(Request $request)
    {
        $validator = PartnerAuthValidators::verify($request->all());
        $this->services->verify($validator);

        return Success(msg: __('main.verified'));
    }

    public function login(Request $request)
    {
        $validator = PartnerAuthValidators::login($request->all());
        [$partner, $token] = $this->services->login($validator);

        return Success(payload: ['user' => $partner, 'token' => $token]);
    }

    public function refreshToken()
    {
        return Success(payload: ['token' => $this->services->refreshToken()]);
    }

    public function logout()
    {
        $this->services->logout();

        return Success(msg: __('main.logout'));
    }

    public function forgetPassword(Request $request)
    {
        $validator = PartnerAuthValidators::forgetPassword($request->all());

        $this->services->forgetPassword($validator);

        return Success(msg: __('main.code sent'));
    }

    public function resetPassword(Request $request)
    {
        $validator = PartnerAuthValidators::resetPassword($request->all());

        $this->services->resetPassword($validator);

        return Success(msg: __('main.password updated'));
    }

    public function resendCode(Request $request)
    {
        $validator = PartnerAuthValidators::resendCode($request->all());
        $this->services->resendCode($validator);

        return Success(msg: __('main.code sent'));
    }

    public function changePassword(Request $request)
    {
        $validator = PartnerAuthValidators::changePassword($request->all());
        $this->services->changePassword($validator);

        return Success(msg: __('main.updated'));
    }
}
