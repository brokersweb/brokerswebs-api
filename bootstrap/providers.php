<?php

use L5Swagger\L5SwaggerServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    // App\Providers\CatchAllOptionsRequestsProvider::class,
    App\Providers\EventServiceProvider::class,
    Spatie\Permission\PermissionServiceProvider::class,
    L5SwaggerServiceProvider::class,
];
