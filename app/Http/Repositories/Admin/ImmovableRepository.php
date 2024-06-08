<?php

namespace App\Http\Repositories\Admin;

use App\Models\Coownership;
use App\Models\CoownershipDetail;
use App\Models\Immovable;
use App\Models\ImmovableOwner;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImmovableRepository extends Repository
{
    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new Immovable();
    }

    public function getAdminValue($id)
    {
        $immovable = $this->model::where('id', $id)->select('id', 'title', 'co_adminvalue as coadminvalue')->first();
        if (!$immovable) {
            return $this->errorResponse('No se encontró el inmueble', Response::HTTP_NOT_FOUND);
        }
        try {
            return response()->json($immovable, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateAdminValue($request, $id)
    {
        $immovable = $this->model::whereId($id)->first();
        if (!$immovable) {
            return $this->errorResponse('No se encontró el inmueble', Response::HTTP_NOT_FOUND);
        }
        $valid = Validator::make($request->all(), [
            'coadminvalue' => 'required',
        ]);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }
        try {
            $coadminvalue = str_replace(',', '', $request->coadminvalue);
            $immovable->update([
                'co_adminvalue' => $coadminvalue,
            ]);
            return $this->successResponse($immovable, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se actualizaba el inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateImmovable($request, $id)
    {
        $immovable = $this->model::whereId($id)->first();

        if (!$immovable) {
            return $this->errorResponse('No se encontró el inmueble', Response::HTTP_NOT_FOUND);
        }

        $valid = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'description' => 'required|max:1000',
            'sale_price' => 'required_if:category,sale|required_if:category,both',
            'immonumber' => 'nullable',
            'rent_price' => 'required_if:category,rent|required_if:category,both',
            'enrollment' => 'nullable|max:255',
            'video_url' => 'nullable|url',
            'image_status' => 'required',
            'video_status' => 'required',
            'immovabletype_id' => 'required|exists:immovabletypes,id',
            'owner_id' => 'required|exists:owners,id',
            'category' => 'required|in:sale,rent,both',
            'useful_parking_room' => 'nullable|in:1,0',
            'bedrooms' => 'required|integer',
            'bathrooms' => 'required|integer',
            'hasparkings' => 'required|in:Si,No',
            'total_area' => 'required',
            'gross_area' => 'required',
            'floor_located' => 'required|integer',
            'stratum' => 'required|integer',
            'unit_type' => 'required|max:255',
            'floor_type_id' => 'required|max:255|exists:floor_types,id',
            'cuisine_type_id' => 'required|max:255|exists:cuisine_types,id',
            'building_company_id' => 'nullable|exists:building_companies,id',
            'co_ownership' => 'required|in:Si,No',
            'co_ownership_id' => 'required_if:co_ownership,Si|exists:coownerships,id',
            'year_construction' => 'nullable',
            'municipality' => 'required|max:255',
            'city' => 'required|max:500',
            'street' => 'required|max:500',
            'neighborhood' => 'nullable|max:500',
            'balcony' => 'required|in:Si,No',
            'terrace' => 'required|in:Si,No',
            'library' => 'required|in:Si,No',
            'domestic_serveroom' => 'required|in:Si,No',
            'alarm' => 'required|in:Si,No',
            'airconditioning' => 'required|in:Si,No',
            'homeautomation' => 'required|in:Si,No',
            'gasnetwork' => 'required|in:Si,No',
            'clotheszone' => 'required|in:Si,No',
            'waterheater' => 'required|in:Si,No',
            'terms' => 'required'
        ]);

        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }

        DB::beginTransaction();
        try {
            // Prices
            $sale_price = str_replace(',', '', $request->sale_price);
            $rent_price = str_replace(',', '', $request->rent_price);
            $request->merge([
                'sale_price' => $sale_price,
                'rent_price' => $rent_price,
            ]);

            $immovable->update([
                'title' => $request->title,
                'description' => $request->description,
                'sale_price' => $request->sale_price,
                'rent_price' => $request->rent_price,
                'enrollment' => $request->enrollment,
                'video_url' => $request->video_url,
                'immovabletype_id' => $request->immovabletype_id,
                'owner_id' => $request->owner_id,
                'category' => $request->category,
                'co_ownership' => $request->co_ownership,
                'immonumber' => $request->immonumber,
                'building_company_id' => $request->building_company_id,
                'image_status' => $request->image_status,
                'video_status' => $request->video_status,
                'terms' => $request->terms,
            ]);
            // Update Details
            $immovable->details()->update([
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'hasparkings' => $request->hasparkings,
                'useful_parking_room' => $request->useful_parking_room,
                'total_area' => $request->total_area,
                'gross_building_area' => $request->gross_area,
                'floor_located' => $request->floor_located,
                'stratum' => $request->stratum,
                'floor_type_id' => $request->floor_type_id,
                'cuisine_type_id' => $request->cuisine_type_id,
                'year_construction' => $request->year_construction,
            ]);
            // Address
            $immovable->address()->update([
                'municipality' => $request->municipality,
                'city' => $request->city,
                'street' => $request->street,
                'neighborhood' => $request->neighborhood,
            ]);
            // Comformts
            $immovable->comformts()->update([
                'balcony' => $request->balcony,
                'patio_or_terrace' => $request->terrace,
                'library' => $request->library,
                'domestic_server_room' => $request->domestic_serveroom,
                'alarm' => $request->alarm,
                'airconditioning' => $request->airconditioning,
                'homeautomation' => $request->homeautomation,
                'gasnetwork' => $request->gasnetwork,
                'clotheszone' => $request->clotheszone,
                'waterheater' => $request->waterheater,
            ]);

            // Update owner to immovable
            $this->setImmovableToOwner($request, $immovable);

            // Si tiene co-propiedad
            if ($request->co_ownership === 'Si') {
                $this->updateWithCoownership($request, $immovable);
            } else {
                $immovable->update([
                    'co_ownership_id' => null,
                ]);
            }

            DB::commit();

            return $this->successResponseWithMessage('Inmueble actualizado exitosamente', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse('Ocurrió un error mientras se actualizaba el inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function setImmovableToOwner($request, $immovable)
    {
        $immoOwner = ImmovableOwner::where('immovable_id', $immovable->id)->first();
        try {
            $immoOwner->update([
                'immovable_id' => $immovable->id,
                'owner_id' => $request->owner_id,
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar el propietario del inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateWithCoownership($request, $immovable)
    {
        // Update Immovable with co-ownership id
        $immovable->update([
            'co_ownership_id' => $request->co_ownership_id,
        ]);

        $valid = Validator::make($request->all(), [
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
            return $this->errorResponseBadRequest($valid->errors());
        }

        $coownership = Coownership::where('id', $request->co_ownership_id)->first();

        if (!$coownership) {
            return $this->errorResponse('No se encontró la copropiedad', Response::HTTP_NOT_FOUND);
        }

        try {
            // Co-ownership details updated
            $coownership->detail()->update([
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
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar la copropiedad', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteImmovable($id)
    {
        $immovable = $this->model::find($id);
        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }
        try {
            $immovable->delete();
            return $this->successResponseWithMessage('Inmueble eliminado correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al eliminar el inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function showUpdate($id)
    {
        $immovable = $this->model::with('details', 'comformts', 'address', 'coownership.detail')->find($id);
        $immovable->makeHidden(['main_image', 'code', 'deleted_at']);

        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($immovable);
    }
}
