<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Renting\ProfessionRepository;
use Illuminate\Http\Request;

class ProfessionController extends Controller
{

    /** @var ProfessionRepository */
    private ProfessionRepository $professionrepository;

    public function __construct(ProfessionRepository $professionrepository)
    {
        $this->professionrepository = $professionrepository;
    }
    /**
     * @OA\Get(
     *     path="/api/professions",
     *     tags={"Profesiones"},
     *     summary="Obtener todas las profesiones",
     *     operationId="professions",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        return $this->professionrepository->professions();
    }
    /**
     * @OA\Post(
     *     path="/api/professions",
     *     tags={"Profesiones"},
     *     summary="Agregar una profesion",
     *     operationId="storeProfession",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *            @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Ingeniero",
     *             ))
     *     ),
     *     @OA\RequestBody(
     *         description="Profession object that needs to be added to the store",
     *         required=true,
     *           @OA\JsonContent(
     *            @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 example="Ingeniero",
     *             ))
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->professionrepository->create($request);
    }
    /**
     * @OA\Put(
     *     path="/api/professions/{id}",
     *     tags={"Profesiones"},
     *     summary="Actualizar una profesion",
     *     operationId="updateProfession",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la profesion",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *    @OA\RequestBody(
     *        description="Profession object that needs to be added to the store",
     *       required=true,
     *      @OA\JsonContent(
     *      @OA\Property(
     *       property="name",
     *       type="string"
     *      )
     * )
     * ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        return $this->professionrepository->update($request, $id);
    }
    /**
     * @OA\Get(
     *     path="/api/professions/{id}",
     *     tags={"Profesiones"},
     *     summary="Obtener una profesion",
     *     operationId="profession",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la profesion",
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
        return $this->professionrepository->profession($id);
    }
    /**
     * @OA\Delete(
     *     path="/api/professions/{id}",
     *     tags={"Profesiones"},
     *     summary="Eliminar una profesion",
     *     operationId="deleteProfession",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The id of the profession is required",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Profession not found",
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profession deleted",
     *     ),
     * )
     */
    public function destroy($id)
    {
        return $this->professionrepository->delete($id);
    }
}
