<?php

namespace KFoobar\Restful;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use KFoobar\Restful\Facades\Restful;
use KFoobar\Restful\LaravelRestful;

class ServiceProvider extends BaseServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();

        $this->app->singleton(LaravelRestful::class, function ($app) {
            return new LaravelRestful($app);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if (Restful::isLoggingEnabled()) {
            $this->registerGroupMiddleware(\KFoobar\Restful\Http\Middleware\LogRequest::class);
        }

        $this->registerMiddlewareAlias('restful.log', \KFoobar\Restful\Http\Middleware\LogRequest::class);

        if ($this->app->runningInConsole()) {
            $this->registerCommands();
            $this->registerAssets();
            $this->registerMigrations();
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/restful.php',
            'restful'
        );
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        $this->commands([
            \KFoobar\Restful\Commands\InstallCommand::class,
            \KFoobar\Restful\Commands\Token\ExtendToken::class,
            \KFoobar\Restful\Commands\Token\GenerateToken::class,
            \KFoobar\Restful\Commands\Token\RevokeToken::class,
            \KFoobar\Restful\Commands\Token\ShowToken::class,
            \KFoobar\Restful\Commands\User\CreateUser::class,
            \KFoobar\Restful\Commands\User\DeleteUser::class,
            \KFoobar\Restful\Commands\User\ShowUser::class,
        ]);
    }

    /**
     * Register publishable assets.
     *
     * @return void
     */
    protected function registerAssets(): void
    {
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'restful-migrations');

        $this->publishes([
            __DIR__.'/../config/restful.php' => config_path('restful.php'),
        ], 'restful-config');
    }

    /**
     * Register migraions.
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register middleware to middleware stack.
     *
     * @param  string $middleware
     *
     * @return void
     */
    protected function registerMiddleware(string $middleware): void
    {
        $kernel = $this->app[Kernel::class];

        $kernel->pushMiddleware($middleware);
    }

    /**
     * Register middleware to the API middleware group.
     *
     * @param  string $middleware
     *
     * @return void
     */
    protected function registerGroupMiddleware(string $middleware): void
    {
        $kernel = $this->app[Kernel::class];

        $kernel->appendMiddlewareToGroup('api', $middleware);
    }

    /**
     * Register middleware alias.
     *
     * @param string $name
     * @param string $middleware
     *
     * @return void
     */
    protected function registerMiddlewareAlias(string $name, string $middleware): void
    {
        $router = $this->app[Router::class];

        $router->aliasMiddleware($name, $middleware);
    }
}
