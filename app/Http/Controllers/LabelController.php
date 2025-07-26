<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

final class LabelController extends Controller
{
    public function partnersTypes(): JsonResponse
    {
        return Success(payload: ['partnersTypes' => DB::table('partners_types')->get()]);
    }

    public function courtsTypes(): JsonResponse
    {
        return Success(payload: ['courtsTypes' => DB::table('courts_types')->get()]);
    }

    public function trainersTypes(): JsonResponse
    {
        return Success(payload: ['trainersTypes' => DB::table('trainers_types')->get()]);
    }

    public function slotsDurations(): JsonResponse
    {
        return Success(payload: ['slotsDurations' => DB::table('slots_durations')->get()]);
    }
}
