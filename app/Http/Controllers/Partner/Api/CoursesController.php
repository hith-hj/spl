<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Enums\PartnersTypes;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Services\CourseServices;
use App\Validators\CourseValidators;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class CoursesController extends Controller
{
    public function __construct(public CourseServices $services) {}

    public function all()
    {
        $courses = $this->services->allByPartner(Auth::user());

        return Success(payload: ['courses' => $courses]);
    }

    public function find(Request $request)
    {
        $validator = CourseValidators::find($request->all());
        $course = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('course_id')
        );

        return Success(payload: [
            'course' => CourseResource::make($course),
        ]);
    }

    public function create(Request $request)
    {
        $validator = CourseValidators::create($request->all());
        $course = $this->services->create(
            Auth::user(),
            $validator->safe()->all()
        );

        return Success(payload: [
            'course' => CourseResource::make($course->fresh()),
        ]);
    }

    public function update(Request $request)
    {
        $validator = CourseValidators::create($request->all(), true);
        $course = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('course_id')
        );
        $this->services->update(
            $course,
            $validator->safe()->except('course_id')
        );

        return Success(payload: [
            'course' => CourseResource::make($course->fresh()),
        ]);
    }

    public function delete(Request $request)
    {
        $validator = CourseValidators::delete($request->all());
        $course = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('course_id')
        );
        $this->services->delete($course);

        return Success(msg: 'course deleted');
    }

    public function main()
    {
        $course = $this->services->mainByPartner(Auth::user());

        return Success(payload: [
            'course' => CourseResource::make($course),
        ]);
    }

    public function setMain(Request $request)
    {
        $validator = CourseValidators::find($request->all());
        $course = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('course_id')
        );
        $this->services->setMain(Auth::user(), $course);

        return Success();
    }

    public function toggleActivation(Request $request)
    {
        $validator = CourseValidators::find($request->all());
        $course = $this->services->findByPartner(
            Auth::user(),
            $validator->safe()->integer('course_id')
        );
        $this->services->toggleActivation($course);

        return Success();
    }
}
