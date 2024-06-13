<?php

namespace App\Http\Controllers;

use App\Models\Coownership;
use App\Models\CoownershipDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoownershipController extends Controller
{

    public function index()
    {
        $coownerships = Coownership::select('name', 'id')->get();
        return $this->successResponse($coownerships);
    }
    public function show($id)
    {
        $codetails = CoownershipDetail::where('coownership_id', $id)->first();
        return $this->successResponse($codetails);
    }


    public function storeModal(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
        ]);

        try {
            $coownership = Coownership::create($request->all());
            $coownership->detail()->create();
            return $this->successResponseWithMessage('Copropiedad agregada', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al crear la copropiedad', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
