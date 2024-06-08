<?php

namespace App\Http\Controllers;

use App\Models\InternalFeature as ModelsInternalFeature;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InternalFeatureController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/internal-features",
     *     tags={"InternalFeatures"},
     *     summary="Obtener todos los tipos de inmuebles",
     *     operationId="internal-features",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $internalfeatures = ModelsInternalFeature::all();
        return $this->successResponse($internalfeatures);
    }
    /**
     * @OA\Get(
     *     path="/api/internal-features/{id}",
     *     tags={"InternalFeatures"},
     *     summary="Obtener un tipo de inmueble",
     *     operationId="internal-features/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del tipo de inmueble",
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
        $internalfeature = ModelsInternalFeature::findOrFail($id);
        return $this->successResponse($internalfeature);
    }
    /**
     * @OA\Post(
     *     path="/api/internal-features",
     *     tags={"store"},
     *     summary="Guardar una caracteristica interna",
     *     operationId="store",
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/InternalFeature"),
     *     ),
     *     @OA\RequestBody(
     *         description="InternalFeature object that needs to be added to the store",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/InternalFeature")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $rules = [
            'description' => 'required|max:255|unique:internalfeatures',
        ];
        $this->validate($request, $rules);
        $internalfeature = ModelsInternalFeature::create($request->all());
        return $this->successResponse($internalfeature, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'description' => 'max:255|unique:internalfeatures',
        ];
        $this->validate($request, $rules);
        $internalfeature = ModelsInternalFeature::findOrFail($id);
        $internalfeature->fill($request->all());
        if ($internalfeature->isClean()) {
            return $this->errorResponse('Al menos un valor debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $internalfeature->save();
        return $this->successResponse($internalfeature);
    }

    public function destroy($id)
    {
        $internalfeature = ModelsInternalFeature::findOrFail($id);
        $internalfeature->delete();
        return $this->successResponse($internalfeature);
    }
}
