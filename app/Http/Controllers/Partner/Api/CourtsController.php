<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourtResource;
use App\Services\CourtServices;
use App\Validators\CourtValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CourtsController extends Controller
{
    public function __construct(public CourtServices $services) {}

    public function all()
    {
        $courts = $this->services->allByPartner(Auth::user());

        return Success(payload: ['courts' => $courts]);
    }

    public function find(Request $request)
    {
        $validator = CourtValidators::find($request->all());
        $court = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('court_id')
        );

        return Success(payload: [
            'court' => CourtResource::make($court),
        ]);
    }

    public function create(Request $request)
    {
        $validator = CourtValidators::create($request->all());
        $court = $this->services->create(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: [
            'court' => CourtResource::make($court->fresh()),
        ]);
    }

    public function update(Request $request)
    {
        $validator = CourtValidators::update($request->all());
        $court = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('court_id')
        );
        $this->services->update(
            $court,
            $validator->safe()->except('court_id')
        );

        return Success(payload: [
            'court' => CourtResource::make($court->fresh()),
        ]);
    }

    public function delete(Request $request)
    {
        $validator = CourtValidators::delete($request->all());
        $court = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('court_id')
        );
        $this->services->delete($court);

        return Success(msg: 'court deleted');
    }

    public function main()
    {
        $court = $this->services->mainByPartner(Auth::user());

        return Success(payload: [
            'court' => CourtResource::make($court),
        ]);
    }

    public function setMain(Request $request)
    {
        $validator = CourtValidators::find($request->all());
        $court = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('court_id')
        );
        $this->services->setMain(Auth::user(), $court);

        return Success();
    }

    public function toggleActivation(Request $request)
    {
        $validator = CourtValidators::find($request->all());
        $court = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('court_id')
        );
        $this->services->toggleActivation($court);

        return Success();
    }
}
