<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Services\TrainerServices;
use Illuminate\Http\Request;

final class TrainersController extends Controller
{
    public function __construct(public TrainerServices $services) {}

    public function all(Request $request)
    {
        return $request->all();
    }

    public function find(Request $request)
    {
        return $request->all();
    }

    public function create(Request $request)
    {
        return $request->all();
    }

    public function update(Request $request)
    {
        return $request->all();
    }

    public function delete(Request $request)
    {
        return $request->all();
    }
}
