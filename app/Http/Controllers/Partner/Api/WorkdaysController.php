<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkdayResource;
use App\Services\WorkdayServices;
use App\Validators\WorkdayValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class WorkdaysController extends Controller
{
    public function __construct(public WorkdayServices $services) {}

    public function all(Request $request)
    {
        $workdays = $this->services->allByPartner(Auth::user());

        return Success(payload: [
            'workdays' => WorkdayResource::collection($workdays),
        ]);
    }

    public function find(Request $request)
    {
        $validator = WorkdayValidators::find($request->all());
        $workday = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('workday_id')
        );

        return Success(payload: [
            'workday' => WorkdayResource::make($workday),
        ]);
    }

    public function create(Request $request)
    {
        $validator = WorkdayValidators::create($request->all());
        $workday = $this->services->create(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: [
            'workday' => WorkdayResource::make($workday->fresh()),
        ]);
    }

    public function update(Request $request)
    {
        $validator = WorkdayValidators::update($request->all());
        $workday = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('workday_id')
        );
        $this->services->update(
            Auth::user(),
            $workday,
            $validator->safe()->except('workday_id')
        );

        return Success(payload: [
            'workday' => WorkdayResource::make($workday->fresh()),
        ]);
    }

    public function delete(Request $request)
    {
        $validator = WorkdayValidators::delete($request->all());
        $workday = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('workday_id')
        );
        $this->services->delete($workday);

        return Success(msg: 'workday deleted');
    }

    public function toggleActivation(Request $request)
    {
        $validator = WorkdayValidators::find($request->all());
        $workday = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('workday_id')
        );
        $this->services->toggleActivation($workday);

        return Success();
    }
}
