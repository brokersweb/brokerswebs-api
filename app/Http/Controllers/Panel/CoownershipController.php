<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ImmovableAdminResource;
use App\Models\Coownership;
use App\Models\CoownershipDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CoownershipController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/coownerships",
     *     tags={"Coownerships"},
     *     summary="Obtener todas las copropiedades",
     *     operationId="coownerships",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $coownerships = Coownership::all();
        return $this->successResponse($coownerships);
    }

    /**
     * @OA\Get(
     *     path="/api/coownerships/{id}",
     *     tags={"Coownerships"},
     *     summary="Obtener una copropiedad",
     *     operationId="coownerships/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id de la copropiedad",
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
            $coownerships = Coownership::whereId($id)->with('detail')->first();
            return $this->successResponse($coownerships);
        } catch (\Exception $e) {
            return $this->errorResponse('Coownership not found', Response::HTTP_NOT_FOUND);
        }
    }
    public function storeModal(Request $request)
    {
        $rules = [
            'name' => 'required|max:255'
        ];

        $this->validate($request, $rules);
        try {
            $coownership = Coownership::create($request->all());
            $coownership->detail()->create();
            return $this->successResponseWithMessage('Copropiedad agregada', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al crear la copropiedad', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'nit' => 'required|max:40',
            'phone' => 'nullable|max:25',
            'cellphone' => 'nullable|max:25',
            'email' => 'nullable|max:255',
            'country' => 'required|max:255',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500|unique:addresses',
            'neighborhood' => 'nullable|max:500',
        ];

        $this->validate($request, $rules);
        $coownerships = Coownership::create($request->all());

        $coownerships->address()->create([
            'addressable_id' => $coownerships->id,
            'addressable_type' => Coownership::class,
            'country' => $request->country,
            'municipality' => $request->municipality,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
        ]);

        return $this->successResponse($coownerships, Response::HTTP_CREATED);
    }
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'nit' => 'required|max:40',
            'phone' => 'nullable|max:25',
            'cellphone' => 'nullable|max:25',
            'email' => 'nullable|max:255',
            'country' => 'required|max:255',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500|unique:addresses',
            'neighborhood' => 'nullable|max:500',
        ];

        $this->validate($request, $rules);
        $coownerships = Coownership::findOrFail($id);
        $coownerships->fill($request->all());
        // Actualizar la dirección
        $coownerships->address()->update([
            'country' => $request->country,
            'municipality' => $request->municipality,
            'city' => $request->city,
            'neighborhood' => $request->neighborhood,
            'street' => $request->street,
        ]);

        if ($coownerships->isClean()) {
            return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $coownerships->save();
        return $this->successResponse($coownerships);
    }

    public function destroy($id)
    {
        try {
            $coownerships = Coownership::findOrFail($id);
            $coownerships->delete();
            return $this->successResponse('Coownership deleted', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Coownership not found', Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * TODO: INMUEBLES
     */
    //  Coownership que tienen inmuebles rented
    public function coownershipsRented()
    {
        $coownerships = Coownership::select('id', 'name')->whereHas('immovables', function ($query) {
            $query->where('status', 'rented');
        })->get();
        return $this->successResponse($coownerships);
    }

    // Inmuebles rentados por copropiedad
    public function immovablesRentedByCoownership($id)
    {
        $coownership = Coownership::findOrFail($id);

        try {
            $immovables = ImmovableAdminResource::collection($coownership->immovables()->where('status', 'rented')->orderBy('created_at', 'desc')->get());
            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Immovables not found', Response::HTTP_NOT_FOUND);
        }
    }

    //  Coownership que tienen inmuebles rented
    public function coownershipsSold()
    {
        $coownerships = Coownership::select('id', 'name')->whereHas('immovables', function ($query) {
            $query->where('status', 'sold');
        })->get();
        return $this->successResponse($coownerships);
    }

    // Inmuebles rentados por copropiedad
    public function immovablesSoldByCoownership($id)
    {
        $coownership = Coownership::findOrFail($id);

        try {
            $immovables = ImmovableAdminResource::collection($coownership->immovables()->where('status', 'sold')->orderBy('created_at', 'desc')->get());
            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Immovables not found', Response::HTTP_NOT_FOUND);
        }
    }
}
