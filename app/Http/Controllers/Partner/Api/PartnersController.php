<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PartnerResource;
use App\Services\PartnerServices;
use App\Validators\PartnerValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class PartnersController extends Controller
{
    public function __construct(public PartnerServices $services) {}

    public function get()
    {
        return Success(payload: [
            'partner' => PartnerResource::make($this->services->get(Auth::id())),
        ]);
    }

    public function update(Request $request)
    {
        return Error('Not Available Now');
        $validator = PartnerValidators::update($request->all());
        $this->services->update(Auth::user(), $validator->safe()->all());

        return Success(msg: 'parnter Updated');
    }

    public function delete()
    {
        $this->services->delete(Auth::id());

        return Success(msg: __('main.deleted'));
    }

    public function createStadium(Request $request)
    {
        $validator = PartnerValidators::stadium($request->all());
        $stadium = $this->services->createStadium(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: ['stadium' => $stadium]);
    }

    public function updateStadium(Request $request)
    {
        $validator = PartnerValidators::stadium($request->all());
        $stadium = $this->services->updateStadium(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success();
    }

    public function createTrainer(Request $request)
    {
        $validator = PartnerValidators::trainer($request->all());
        $trainer = $this->services->createTrainer(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: ['trainer' => $trainer]);
    }

    public function updateTrainer(Request $request)
    {
        $validator = PartnerValidators::trainer($request->all());
        $trainer = $this->services->updateTrainer(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success();
    }
}
