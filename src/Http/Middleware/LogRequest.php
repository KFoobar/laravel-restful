<?php

namespace KFoobar\Restful\Http\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use KFoobar\Restful\Facades\Restful;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogRequest
{
    /**
     * The URIs that should be excluded from logging.
     *
     * @var array
     */
    protected $except = [];

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->isUnitTests() || $this->inExceptArray($request)) {
            return $next($request);
        }

        Restful::startRequestLogging($request);

        return $next($request);
    }

    /**
     * Perform any final actions for the request lifecycle.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @param  \Symfony\Component\HttpFoundation\Response  $response
     * @return void
     */
    public function terminate(Request $request, Response $response): void
    {
        if (!Restful::isResquestLogged($request)) {
            return;
        }

        Restful::stopRequestLogging($request, $response);
    }

    /**
     * Determine if the request has a URI that should be excepted from logging.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function inExceptArray($request): bool
    {
        $excetps = array_merge(
            config('restful.log.except') ?? [],
            $this->except ?? []
        );

        foreach ($excetps as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the application is running unit tests.
     *
     * @return bool
     */
    protected function isUnitTests()
    {
        return $this->app->runningInConsole() && $this->app->runningUnitTests();
    }
}
