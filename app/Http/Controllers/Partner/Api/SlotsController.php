<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Http\Controllers\Controller;
use App\Services\SlotServices;
use Illuminate\Http\Request;

final class SlotsController extends Controller
{
    public function __construct(public SlotServices $services) {}

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
