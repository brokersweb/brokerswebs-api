<?php

namespace App\Providers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        $this->app['auth']->viaRequest('api', function ($request) {
            $auth = $request->header('Authorization');
            if ($auth === '') {
                return null;
            }
            $auth = explode(' ', $auth);
            if ($auth[0] !== 'Bearer') {
                return null;
            }
            $token = $auth[1];
            try {
                $credentials = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
                return User::find($credentials->sub);
            } catch (\Throwable $e) {
                return null;
            }
        });
    }
}
