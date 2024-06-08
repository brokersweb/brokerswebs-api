<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CatchAllOptionsRequestsProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $request = app('request');
        if ($request->isMethod('OPTIONS')) {
            app()->options($request->path(), function () {
                return response('', 200);
            });
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
