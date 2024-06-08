<?php

namespace App\Http\Controllers;

use App\Models\ExternalFeature as ModelsExternalFeature;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExternalFeatureController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/external-features",
     *     tags={"ExternalFeatures"},
     *     summary="Obtener todos los tipos de inmuebles",
     *     operationId="external-features",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $externalfeatures = ModelsExternalFeature::all();
        return $this->successResponse($externalfeatures);
    }

    /**
     * @OA\Get(
     *     path="/api/external-features/{id}",
     *     tags={"ExternalFeatures"},
     *     summary="Obtener un tipo de inmueble",
     *     operationId="external-features/{id}",
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
        $externalfeature = ModelsExternalFeature::findOrFail($id);
        return $this->successResponse($externalfeature);
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required|max:255|unique:externalfeatures',
        ];
        $this->validate($request, $rules);
        $externalfeature = ModelsExternalFeature::create($request->all());
        return $this->successResponse($externalfeature, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'description' => 'max:255|unique:externalfeatures',
        ];
        $this->validate($request, $rules);
        $externalfeature = ModelsExternalFeature::findOrFail($id);
        $externalfeature->fill($request->all());
        if ($externalfeature->isClean()) {
            return $this->errorResponse('Al menos un valor debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $externalfeature->save();
        return $this->successResponse($externalfeature);
    }

    public function destroy($id)
    {
        $externalfeature = ModelsExternalFeature::findOrFail($id);
        $externalfeature->delete();
        return $this->successResponse($externalfeature);
    }
}
