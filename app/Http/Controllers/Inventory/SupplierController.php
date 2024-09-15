<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{

    public function index()
    {
        $suppliers = Supplier::orderBy('created_at', 'desc')->get();
        return $this->successResponse($suppliers);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'nit' => 'required|unique:suppliers,nit',
            'contact_person' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'photo' => 'nullable',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $supplier = Supplier::create($request->all());

        return $this->successResponseWithMessage('Proveeder agregado exitosamente', Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $supplier = Supplier::find($id);
            return $this->successResponse($supplier);
        } catch (\Throwable $th) {
            return $this->errorResponse('Proveedor no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'nit' => [
                'required',
                Rule::unique('suppliers', 'nit')->ignore($id),
            ],
            'contact_person' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
            'address' => 'nullable',
            'photo' => 'nullable',
        ]);


        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $supplier = Supplier::find($id);
        $supplier->update($request->all());

        return $this->successResponseWithMessage('Proveedor actualizado exitosamente', Response::HTTP_OK);
    }

    public function destroy($id)
    {
        try {
            $supplier = Supplier::find($id);

            if (!$supplier) {
                return $this->errorResponse('Proveedor no encontrado', Response::HTTP_NOT_FOUND);
            }
            $supplier->delete();
            return $this->successResponseWithMessage('Proveedor eliminado exitosamente', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Error al eliminar el proveedor', Response::HTTP_BAD_REQUEST);
        }
    }
}
