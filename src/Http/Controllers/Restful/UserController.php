<?php

namespace KFoobar\Restful\Http\Controllers\Restful;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Shows the profile information.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function profile(): JsonResponse
    {
        return response()->json([
            'user' => auth()->user()->email,
        ], JsonResponse::HTTP_OK);
    }
}
