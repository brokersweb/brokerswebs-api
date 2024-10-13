<?php
namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Renting\ReferenceRepository;
use Illuminate\Http\Request;

class ReferenceController extends Controller
{

    private ReferenceRepository $repository;

    public function __construct(ReferenceRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *     path="/api/references",
     *     tags={"Reference"},
     *     summary="Obtener todas las referencias",
     *     operationId="references",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        return $this->repository->references();
    }

    /**
     * @OA\Get(
     *     path="/api/references/{id}",
     *     tags={"Reference"},
     *     summary="Obtener una referencia",
     *     operationId="reference",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la referencia",
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
        return $this->repository->reference($id);
    }
    /**
     * @OA\Post(
     *     path="/api/references",
     *     tags={"Reference"},
     *     summary="Agregar una referencia",
     *     operationId="storeReference",
     *    @OA\RequestBody(
     *        description="Referencia a almacenar",
     *       required=true,
     *      @OA\JsonContent(
     *      @OA\Property(
     *       property="name",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="lastname",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_address",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_country",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_department",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_city",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="kinship",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="phone",
     *       type="string"
     *      ),
     *     @OA\Property(
     *       property="applicant_id",
     *       type="integer"
     *      ),
     * )
     * ),
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function store(Request $request)
    {
        return $this->repository->create($request);
    }
    /**
     * @OA\Put(
     *     path="/api/references/{id}",
     *     tags={"Reference"},
     *     summary="Actualizar una referencia",
     *     operationId="updateReference",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la referencia",
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
     *      ),
     *      @OA\Property(
     *       property="lastname",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_address",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_country",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_department",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="residence_city",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="kinship",
     *       type="string"
     *      ),
     *      @OA\Property(
     *       property="phone",
     *       type="string"
     *      ),
     *     @OA\Property(
     *       property="applicant_id",
     *       type="integer"
     *      ),
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
        return $this->repository->update($request, $id);
    }
    /**
     * @OA\Delete(
     *     path="/api/references/{id}",
     *     tags={"Reference"},
     *     summary="Eliminar una referencia",
     *     operationId="referencedel",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la referencia",
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
    public function destroy($id)
    {
        return $this->repository->delete($id);
    }
}
