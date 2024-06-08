<?php

namespace App\Http\Controllers;

use App\Models\CommonZone as ModelsCommonZone;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommonZoneController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/common-zones",
     *     tags={"CommonZones"},
     *     summary="Obtener todas las zonas comunes",
     *     operationId="common-zones",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $commonzones = ModelsCommonZone::all();
        return $this->successResponse($commonzones);
    }

    /**
     * @OA\Get(
     *     path="/api/common-zones/{id}",
     *     tags={"CommonZones"},
     *     summary="Obtener una zona común",
     *     operationId="common-zones/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la zona común",
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
        $commonzone = ModelsCommonZone::findOrFail($id);
        return $this->successResponse($commonzone);
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required|max:255|unique:commonzones',
        ];
        $this->validate($request, $rules);
        $commonzone = ModelsCommonZone::create($request->all());
        return $this->successResponse($commonzone, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'description' => 'max:255|unique:commonzones',
        ];
        $this->validate($request, $rules);
        $commonzone = ModelsCommonZone::findOrFail($id);
        $commonzone->fill($request->all());
        if ($commonzone->isClean()) {
            return $this->errorResponse('Al menos un valor debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $commonzone->save();
        return $this->successResponse($commonzone);
    }

    public function destroy($id)
    {
        $commonzone = ModelsCommonZone::findOrFail($id);
        $commonzone->delete();
        return $this->successResponse($commonzone);
    }
}
