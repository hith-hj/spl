<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\FeeResource;
use App\Http\Validators\FeeValidators;
use App\Services\FeeServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

final class FeesController extends Controller
{
    public function __construct(public FeeServices $services) {}

    public function all(): JsonResponse
    {
        $fees = $this->services->get(Auth::user()->sub);

        return Success(payload: ['fees' => FeeResource::collection($fees)]);
    }

    public function find(Request $request): JsonResponse
    {
        $validator = FeeValidators::find($request->all());

        $fee = $this->services->find($validator->safe()->integer('fee_id'));

        return Success(payload: ['fee' => FeeResource::make($fee)]);
    }
}
