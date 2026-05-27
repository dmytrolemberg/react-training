<?php

declare(strict_types = 1);

namespace App\Client\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class PingController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'section' => 'client',
            'version' => 'v1',
        ]);
    }
}
