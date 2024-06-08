<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\BuildingCompany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingCompanyController extends Controller
{

       /**
     * @OA\Get(
     *     path="/api/buildings",
     *     tags={"Buildings"},
     *     summary="Obtener todas las constructoras",
     *     operationId="buildings",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $buildings = BuildingCompany::all();
        return $this->successResponse($buildings);
    }
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:building_companies',
        ];
        $this->validate($request, $rules);
        try {
            BuildingCompany::create($request->all());
            return $this->successResponseWithMessage('Constructora agregada', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al crear la constructora', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * @OA\Get(
     *     path="/api/buildings/{id}",
     *     tags={"Buildings"},
     *     summary="Obtener una constructora",
     *     operationId="buildings/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la constructora",
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
        try {
            $buildings = BuildingCompany::whereId($id)->with('address')->first();
            return $this->successResponse($buildings);
        } catch (\Exception $e) {
            return $this->errorResponse('BuildingCompany not found', Response::HTTP_NOT_FOUND);
        }
    }


    public function store2(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'nit' => 'required|max:40',
            'phone' => 'nullable|max:25',
            'cellphone' => 'nullable|max:25',
            'email' => 'nullable|max:255',
            'url_website' => 'nullable',
            'country' => 'required|max:255',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500|unique:addresses',
            'neighborhood' => 'nullable|max:500',
        ];

        $this->validate($request, $rules);
        $buildings = BuildingCompany::create($request->all());

        $buildings->address()->create([
            'addressable_id' => $buildings->id,
            'addressable_type' => BuildingCompany::class,
            'country' => $request->country,
            'municipality' => $request->municipality,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
        ]);

        return $this->successResponse($buildings, Response::HTTP_CREATED);
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'nit' => 'required|max:40',
            'phone' => 'nullable|max:25',
            'cellphone' => 'nullable|max:25',
            'email' => 'nullable|max:255',
            'url_website' => 'nullable',
            'country' => 'required|max:255',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500|unique:addresses',
            'neighborhood' => 'nullable|max:500',
        ];

        $this->validate($request, $rules);
        $buildings = BuildingCompany::findOrFail($id);
        $buildings->fill($request->all());
        // Actualizar la dirección
        $buildings->address()->update([
            'country' => $request->country,
            'municipality' => $request->municipality,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
        ]);

        if ($buildings->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $buildings->save();
        return $this->successResponse($buildings);
    }

    // Eliminar una constructora

    public function destroy($id)
    {
        try {
            $buildings = BuildingCompany::findOrFail($id);
            $buildings->delete();
            return $this->successResponse('BuildingCompany deleted', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('BuildingCompany not found', Response::HTTP_NOT_FOUND);
        }
    }
}
