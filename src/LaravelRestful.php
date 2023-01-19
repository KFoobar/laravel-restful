<?php

namespace KFoobar\Restful;

use App\Models\Request\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use KFoobar\Restful\Responses\ServiceResponse;
use Symfony\Component\HttpFoundation\Response;

class LaravelRestful
{
    /**
     * Determines if logging is enabled.
     *
     * @return bool
     */
    public function isLoggingEnabled(): bool
    {
        return config('restful.log.enabled');
    }

    /**
     * Starts a request logging.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return void
     */
    public function startRequestLogging(Request $request): void
    {
        $request->restful = (object) [
            'uuid' => (string) Str::uuid(),
            'start' => microtime(true),
        ];
    }

    /**
     * Stops a request logging.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     *
     *
     * @return void
     */
    public function stopRequestLogging(Request $request, Response $response): void
    {
        $this->storeRequestLogging([
            'uuid'     => (string) $request->restful->uuid,
            'user_id'  => auth()->check() ? auth()->id() : null,
            'url'      => $request->fullUrl(),
            'route'    => $request->route()->getName(),
            'method'   => $request->getMethod(),
            'ip'       => $request->getClientIp(),
            'time'     => round(microtime(true) - $request->restful->start, 4),
            'request'  => $request->all(),
            'response' => $response->getContent(),
        ]);
    }

    /**
     * Stores a request logging.
     *
     * @param array $data
     *
     * @return void
     */
    public function storeRequestLogging(array $data): void
    {
        RequestLog::create($data);
    }

    /**
     * Determines whether the specified request is being logged.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    public function isResquestLogged(Request $request): bool
    {
        return !empty($request->restful) ? true : false;
    }

    /**
     * Adds a message response.
     *
     * @return void
     */
    public function addMessageResponse(): void
    {
        response()->macro('message', function (...$args) {
            return ServiceResponse::message(...$args);
        });
    }

    /**
     * Adds a success response.
     *
     * @return void
     */
    public function addSuccessResponse(): void
    {
        response()->macro('success', function (...$args) {
            return ServiceResponse::success(...$args);
        });
    }

    /**
     * Adds an error response.
     *
     * @return void
     */
    public function addErrorResponse(): void
    {
        response()->macro('error', function (...$args) {
            return ServiceResponse::error(...$args);
        });
    }
}
