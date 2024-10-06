<?php

namespace App\Providers;

use App\Helpers\NumberToWords;
use App\Models\Immovable;
use App\Models\Inventory\InventoryEntrance;
use App\Models\Inventory\OperationMaterial;
use App\Models\Inventory\ServiceOrder;
use App\Models\User;
use App\Observers\ImmovableObserver;
use App\Observers\InventoryEntranceObserver;
use App\Observers\OperationMaterialObserver;
use App\Observers\OrderServiceObserver;
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

        // TODO:: Observadores
        OperationMaterial::observe(OperationMaterialObserver::class);
        Immovable::observe(ImmovableObserver::class);

        ServiceOrder::observe(OrderServiceObserver::class);
        InventoryEntrance::observe(InventoryEntranceObserver::class);
    }
}
