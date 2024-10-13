<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AddressController extends Controller
{

     /**
     * @OA\Get(
     *     path="/api/addresses",
     *     tags={"Addresses"},
     *     summary="Obtener todas las direcciones",
     *     operationId="addresses",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $addresses = Address::all();
        return $this->successResponse($addresses);

    }
    /**
     * @OA\Get(
     *     path="/api/addresses/{id}",
     *     tags={"Addresses"},
     *     summary="Obtener una dirección",
     *     operationId="addresses/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la dirección",
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
            $address = Address::findOrFail($id);
            return $this->successResponse($address);
        } catch (\Exception $e) {
            return $this->errorResponse('Address not found', Response::HTTP_NOT_FOUND);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'addressable_id' => 'required|integer',
            'addressable_type' => 'required',
            'country' => 'required|max:255',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500|unique:addresses',
            'neighborhood' => 'max:500',
        ];

        $this->validate($request, $rules);
        $address = Address::create($request->all());
        return $this->successResponse($address, Response::HTTP_CREATED);
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'addressable_id' => 'required|integer',
            'addressable_type' => 'required',
            'country' => 'required|max:255',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500',
            'neighborhood' => 'max:500',
        ];

        $this->validate($request, $rules);
        $address = Address::findOrFail($id);
        $address->fill($request->all());

        if ($address->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $address->save();
        return $this->successResponse($address);
    }

    /**
     * @OA\Delete(
     *     path="/api/addresses/{id}",
     *     tags={"Addresses"},
     *     summary="Eliminar una dirección",
     *     operationId="addressess/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la dirección",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="successful operation"
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $address = Address::findOrFail($id);
            $address->delete();
            return $this->successResponse('Address deleted', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Address not found', Response::HTTP_NOT_FOUND);
        }
    }
}
