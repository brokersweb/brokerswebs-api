<?php

namespace App\Http\Controllers;

use App\Models\ImmovableType as ModelsImmovableType;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImmovableTypeController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/immovable-types",
     *     tags={"ImmovableTypes"},
     *     summary="Obtener todos los tipos de inmuebles",
     *     operationId="immovable-types",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $immovabletypes = ModelsImmovableType::all();
        return $this->successResponse($immovabletypes);
    }

    /**
     * @OA\Get(
     *     path="/api/immovable-types/{id}",
     *     tags={"ImmovableTypes"},
     *     summary="Obtener un tipo de inmueble",
     *     operationId="immovable-types/{id}",
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
        $immovabletype = ModelsImmovableType::findOrFail($id);
        return $this->successResponse($immovabletype);
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required|max:255|unique:immovabletypes',
        ];
        $this->validate($request, $rules);
        $immovabletype = ModelsImmovableType::create($request->all());
        return $this->successResponse($immovabletype, Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'description' => 'max:255|unique:immovabletypes',
        ];
        $this->validate($request, $rules);
        $immovabletype = ModelsImmovableType::findOrFail($id);
        $immovabletype->fill($request->all());
        if ($immovabletype->isClean()) {
            return $this->errorResponse('Al menos un valor debe cambiar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $immovabletype->save();
        return $this->successResponse($immovabletype);
    }

    
    public function destroy($id)
    {
        $immovabletype = ModelsImmovableType::findOrFail($id);
        $immovabletype->delete();
        return $this->successResponse($immovabletype);
    }
}
