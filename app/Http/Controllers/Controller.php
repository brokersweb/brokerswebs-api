<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *     description="Esta es la documentación para la API que manejará la información de las propiedades o inmuebles  You can find
 out more about Swagger at
[http://swagger.io](http://swagger.io) or on
[irc.freenode.net, #swagger](http://swagger.io/irc/).",
 *     version="1.0.0",
 *     title="Brokers Web API",
 *     termsOfService="http://swagger.io/terms/",
 *     @OA\Contact(
 *         email="yocumo1998@gmail.com"
 *     ),
 *     @OA\License(
 *         name="Apache 2.0",
 *         url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 */
abstract class Controller extends BaseController
{
    use ApiResponse;
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
