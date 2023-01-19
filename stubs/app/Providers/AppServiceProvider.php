<?php

namespace App\Providers;

use App\Models\Sanctum\PersonalAccessToken;
use Illuminate\Support\ServiceProvider;
use KFoobar\Restful\Facades\Restful;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Sanctum::usePersonalAccessTokenModel(
            PersonalAccessToken::class
        );

        Restful::addMessageResponse();
        Restful::addSuccessResponse();
        Restful::addErrorResponse();
    }
}
