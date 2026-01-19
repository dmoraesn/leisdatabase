<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\IbgeService;
use Illuminate\Http\JsonResponse;

class IbgeController extends Controller
{
    public function estados(IbgeService $service): JsonResponse
    {
        return response()->json(
            $service->estados()
        );
    }

    public function cidades(string $uf, IbgeService $service): JsonResponse
    {
        return response()->json(
            $service->cidades($uf)
        );
    }
}
