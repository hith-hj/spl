<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocationResource;
use App\Services\LocationServices;
use App\Validators\LocationValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class LocationsController extends Controller
{
    public function __construct(public LocationServices $services) {}

    public function get(Request $request)
    {
        return Success(payload: [
            'locations' => LocationResource::make(
                $this->services->get(Auth::user())
            ),
        ]);
    }

    public function create(Request $request)
    {
        $validator = LocationValidators::create($request->all());
        if ($this->services->checkLocationExists(Auth::user())) {
            return Error(msg: 'location exists');
        }

        $location = $this->services->create(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: [
            'location' => LocationResource::make($location),
        ]);
    }

    public function update(Request $request)
    {
        $validator = LocationValidators::create($request->all(), true);
        $location = $this->services->update(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success();
    }

    public function delete(Request $request)
    {
        $this->services->delete(Auth::user());

        return Success();
    }
}
