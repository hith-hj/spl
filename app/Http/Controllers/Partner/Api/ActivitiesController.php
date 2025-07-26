<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Services\ActivityServices;
use App\Validators\ActivityValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class ActivitiesController extends Controller
{
    public function __construct(public ActivityServices $services) {}

    public function all()
    {
        $activities = $this->services->allByPartner(Auth::user());

        return Success(payload: [
            'activities' => ActivityResource::collection($activities),
        ]);
    }

    public function find(Request $request)
    {
        $validator = ActivityValidators::find($request->all());
        $activity = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('activity_id')
        );

        return Success(payload: [
            'activity' => ActivityResource::make($activity),
        ]);
    }

    public function create(Request $request)
    {
        $validator = ActivityValidators::create($request->all());
        $activity = $this->services->create(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: [
            'activity' => ActivityResource::make($activity->fresh()),
        ]);
    }

    public function update(Request $request)
    {
        $validator = ActivityValidators::create($request->all(), true);
        $activity = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('activity_id')
        );
        // TODO: check if the activity has any appointment prevent update ad diactivate
        $this->services->update(
            Auth::user(),
            $activity,
            $validator->safe()->except('activity_id')
        );

        return Success(payload: [
            'activity' => ActivityResource::make($activity->fresh()),
        ]);
    }

    public function delete(Request $request)
    {
        $validator = ActivityValidators::delete($request->all());
        $activity = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('activity_id')
        );
        $this->services->delete($activity);

        return Success(msg: 'activity deleted');
    }

    public function toggleActivation(Request $request)
    {
        $validator = ActivityValidators::find($request->all());
        $activity = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('activity_id')
        );
        $this->services->toggleActivation($activity);

        return Success();
    }
}
