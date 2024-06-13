<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\CoownershipAddRequest;
use App\Http\Resources\Admin\ImmovableAdminResource;
use App\Models\Coownership;
use App\Models\CoownershipDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

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
    public function store2(Request $request)
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

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'elevator' => 'required|in:Si,No',
            'intercom' => 'required|in:Si,No',
            'garbage_shut' => 'required|in:Si,No',
            'visitor_parking' => 'required|in:Si,No',
            'social_room' => 'required|in:Si,No',
            'sports_court' => 'required|in:Si,No',
            'bbq_area' => 'required|in:Si,No',
            'childish_games' => 'required|in:Si,No',
            'parkland' => 'required|in:Si,No',
            'jogging_track' => 'required|in:Si,No',
            'jacuzzi' => 'required|in:Si,No',
            'turkish' => 'required|in:Si,No',
            'gym' => 'required|in:Si,No',
            'closed_circuit_tv' => 'required|in:Si,No',
            'climatized_pool' => 'required|in:Si,No',
            'goal' => 'required|in:Si,No',
            'goal_hours' => 'nullable',
            'petfriendly_zone' => 'required|in:Si,No',
        ]);

        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors(), Response::HTTP_BAD_REQUEST);
        }


        try {
            $coownership = Coownership::create([
                'name' => $request->name,
            ]);

            CoownershipDetail::create([
                'coownership_id' => $coownership->id,
                'elevator' => $request->elevator,
                'intercom' => $request->intercom,
                'garbage_shut' => $request->garbage_shut,
                'visitor_parking' => $request->visitor_parking,
                'social_room' => $request->social_room,
                'sports_court' => $request->sports_court,
                'bbq_area' => $request->bbq_area,
                'childish_games' => $request->childish_games,
                'parkland' => $request->parkland,
                'jogging_track' => $request->jogging_track,
                'jacuzzi' => $request->jacuzzi,
                'turkish' => $request->turkish,
                'gym' => $request->gym,
                'closed_circuit_tv' => $request->closed_circuit_tv,
                'climatized_pool' => $request->climatized_pool,
                'goal' => $request->goal,
                'goal_hours' => $request->goal_hours,
                'petfriendly_zone' => $request->petfriendly_zone,
            ]);

            return $this->successResponse($coownership->name, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al crear la unidad/copropiedad', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
