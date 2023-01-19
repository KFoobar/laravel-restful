<?php

namespace KFoobar\Restful\Responses;

use Illuminate\Http\JsonResponse;

class ServiceResponse
{
    /**
     * Returns a message response.
     *
     * @param string $message
     * @param int    $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function message(string $message, ?int $status = null): JsonResponse
    {
        return response()->json([
            'message' => $message ?? null,
        ], $status ?? JsonResponse::HTTP_OK);
    }

    /**
     * Returns successful response.
     *
     * @param mixed $data
     * @param int   $status
     * @param mixed $meta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success(mixed $data, int $status = null, mixed $meta = []): JsonResponse
    {
        $body = [
            'data' => $data ?? null,
        ];

        if (!empty($meta)) {
            $meta = is_array($meta) ? $meta : [];
            $meta = array_merge($meta, [
                'hits'      => is_countable($data) ? count($data) : 0,
                'timestamp' => request()->server('REQUEST_TIME'),
            ]);

            $body['meta'] = $meta;
        }

        return response()->json($body, $status ?? JsonResponse::HTTP_OK);
    }

    /**
     * Return error response.
     *
     * @param mixed $errors
     * @param int   $status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function error(mixed $errors, ?string $message = null, ?int $status = null): JsonResponse
    {
        return response()->json([
            'error'  => [
                'code'    => $status ?? JsonResponse::HTTP_BAD_REQUEST,
                'message' => $message ?? null,
                'details' => $errors ?? null,
            ]
        ], $status ?? JsonResponse::HTTP_BAD_REQUEST);
    }
}
