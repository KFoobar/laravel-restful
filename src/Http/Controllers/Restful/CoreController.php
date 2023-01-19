<?php

namespace KFoobar\Restful\Http\Controllers\Restful;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use KFoobar\Restful\Facades\Restful;

class CoreController extends Controller
{
    /**
     * Shows the ping information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ping(): JsonResponse
    {
        return response()->json([
            'running'   => true,
            'php'       => phpversion(),
            'framework' => app()->version(),
            'timestamp' => now()->timestamp,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Shows the fallback information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function fallback(): JsonResponse
    {
        return response()
            ->message('Endpoint not found');
    }
}
