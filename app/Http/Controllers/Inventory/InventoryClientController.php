<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use PhpParser\Node\Stmt\Return_;

class InventoryClientController extends Controller
{

    public function index()
    {

        $customers = InventoryClient::orderBy('created_at', 'DESC')->get();
        return $this->successResponse($customers);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'required',
            'address' => 'required',
            'nit' => 'nullable',
            'dni' => 'nullable',
            'rut' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $customer = InventoryClient::create($request->all());
            return $this->successResponseWithMessage('Cliente creado de manera exitosa.');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
