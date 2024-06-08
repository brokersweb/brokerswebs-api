<?php

namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Renting\CosignerRepository;

class CosignerController extends Controller
{

    /** @var CosignerRepository */
    private CosignerRepository $cosignerrepository;

    public function __construct(CosignerRepository $cosignerrepository)
    {
        $this->cosignerrepository = $cosignerrepository;
    }

    /**
     * @OA\Get(
     *     path="/api/cosigners",
     *     tags={"Cosigner"},
     *     summary="Obtener todos los codeudores",
     *     operationId="cosigners",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        return $this->cosignerrepository->cosigners();
    }
    /**
     * @OA\Get(
     *     path="/api/cosigners/{id}",
     *     tags={"Cosigner"},
     *     summary="Obtener un codeudor",
     *     operationId="cosigner",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del codeudor",
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
        return $this->cosignerrepository->cosigner($id);
    }
    /**
     * @OA\Delete(
     *     path="/api/cosigners/{id}",
     *     tags={"Cosigner"},
     *     summary="Eliminar un codeudor",
     *     operationId="cosigner",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del codeudor",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation"
     *     ),
     * *     @OA\Response(
     *         response="404",
     *         description="Codeudor no encontrado"
     *     ),
     * )
     */
    public function destroy($id)
    {
        return $this->cosignerrepository->delete($id);
    }
}
