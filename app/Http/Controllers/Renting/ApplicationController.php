<?php

namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Renting\ApplicationRepository;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    protected ApplicationRepository $repository;

    public function __construct(ApplicationRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/api/applications",
     *     tags={"Application"},
     *     summary="Obtener todas las aplicaciones o solicitudes",
     *     operationId="applications",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        return $this->repository->all();
    }
    /**
     * @OA\Get(
     *     path="/api/applications/{id}",
     *     tags={"Application"},
     *     summary="Obtener una aplicacion o solicitud",
     *     operationId="application",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la aplicacion o solicitud",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function show($id)
    {
        return $this->repository->show($id);
    }
    // Cambiar el estado de la solicitud
    /**
     * @OA\Put(
     *     path="/api/applications/{id}/status",
     *     tags={"Application"},
     *     summary="Cambiar el estado de la solicitud",
     *     operationId="changeStatus",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la aplicacion o solicitud",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Estado de la solicitud",
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="Accepted"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */

    public function changeStatus(Request $request, $id)
    {
        return $this->repository->changeStatus($request, $id);
    }
}
