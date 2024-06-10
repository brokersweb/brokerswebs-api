<?php

namespace App\Providers;

use App\Helpers\NumberToWords;
use App\Models\User;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        date_default_timezone_set('America/Bogota');
        // Register the service the package provides.
        $this->app->bind(
            'App\Repositories\Renting\CosignerRepository',
        );

        $this->app->bind(
            'App\Repositories\Renting\ApplicationRepository',
        );

        $this->app->bind('number-to-words', function () {
            return new NumberToWords();
        });
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app['auth']->viaRequest('api', function ($request) {
            $token = $request->get('token') ?? $request->bearerToken();
            if ($token) {
                try {
                    $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                    return User::find($credentials->sub);
                } catch (ExpiredException $e) {
                    //Provided token is expired.
                } catch (\Exception $e) {
                    //An error while decoding token.
                }
            }
        });
    }
}
