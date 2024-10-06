<?php

namespace App\Http\Controllers;

use App\Helpers\ImmovableCodeGenerator;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Resources\ImmovableRentingResource;
use App\Http\Resources\ImmovableResource;
use App\Http\Resources\Inventory\ImmovableOperationResource;
use App\Models\BuildingCompany;
use App\Models\Coownership;
use App\Models\CoownershipDetail;
use App\Models\Base\Gallery;
use App\Models\Immovable as ModelsImmovable;
use App\Models\ImmovableOwner;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImmovableController extends Controller
{

    private UtilsController $utilsController;

    public function __construct()
    {
        $this->utilsController = new UtilsController();
    }

    /**
     * @OA\Get(
     *     path="/api/immovables",
     *     tags={"Immovables"},
     *     summary="Obtener todos los inmuebles",
     *     operationId="immovables",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $immovables = ImmovableResource::collection(ModelsImmovable::orderBy('created_at', 'desc')
            ->where('image_status', 'accepted')
            ->where('status', 'active')
            ->get());
        return $this->successResponse($immovables);
    }

    public function indexToRenting()
    {
        $immovables = ImmovableRentingResource::collection(ModelsImmovable::orderBy('created_at', 'desc')
            ->where('image_status', 'accepted')
            ->where('status', 'active')
            ->where('category', 'rent')
            ->get());
        return $this->successResponse($immovables);
    }


    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'main_image' => 'required',
            'description' => 'required|max:1000',
            'sale_price' => 'nullable',
            'immonumber' => 'nullable',
            'rent_price' => 'nullable',
            'enrollment' => 'nullable|max:255',
            'video_url' => 'nullable|url',
            'immovabletype_id' => 'required|exists:immovabletypes,id',
            'owner_id' => 'required|exists:users,id',
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
            // 'know_building_company' => 'required|in:0,1',
            // 'building_company_name' => 'required_if:know_building_company,0|max:255',
            'building_company_id' => 'nullable',
            'co_ownership' => 'required|in:Si,No',
            'co_ownership_id' => 'required_if:co_ownership,Si',
            'year_construction' => 'nullable',
            'tower' => 'nullable',
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
            'images_gallery' => 'required|array',
            'terms' => 'required'
        ]);

        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Eliminar las comas de los precios
        $sale_price = str_replace(',', '', $request->sale_price);
        $rent_price = str_replace(',', '', $request->rent_price);
        $request->merge([
            'sale_price' => $sale_price,
            'rent_price' => $rent_price,
        ]);
        DB::beginTransaction();
        try {

            $code = ImmovableCodeGenerator::generateCode($request->city, $request->municipality);

            $immovable = ModelsImmovable::create([
                'title' => $request->title,
                'code' => $code,
                'main_image' => $request->main_image,
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
                'status' => 'inactive',
                'terms' => $request->terms,
            ]);

            // Details Added
            $immovable->details()->create([
                'immovable_id' => $immovable->id,
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'hasparkings' => $request->hasparkings,
                'useful_parking_room' => $request->useful_parking_room,
                'total_area' => $request->total_area,
                'gross_building_area' => $request->gross_area,
                'floor_located' => $request->floor_located,
                'stratum' => $request->stratum,
                'unit_type' => $request->unit_type,
                'floor_type_id' => $request->floor_type_id,
                'cuisine_type_id' => $request->cuisine_type_id,
                'year_construction' => $request->year_construction,
                'tower' => $request->tower,
            ]);
            // Address Added
            $immovable->address()->create([
                'addressable_id' => $immovable->id,
                'addressable_type' => ModelsImmovable::class,
                'municipality' => $request->municipality,
                'city' => $request->city,
                'street' => $request->street,
                'neighborhood' => $request->neighborhood,
            ]);
            // Comformts Added
            $immovable->comformts()->create([
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
            // Si tiene co-propiedad
            if ($request->co_ownership == 'Si') {
                $immovable->update([
                    'co_ownership_id' => $request->co_ownership_id,
                ]);
            }

            if ($request->building_company_id != '') {
                $immovable->update([
                    'building_company_id' => $request->building_company_id,
                ]);
            }

            // Agregar Imágenes a la galeria
            if ($request->has('images_gallery')) {
                foreach ($request->images_gallery as $image) {
                    $immovable->galleries()->create([
                        'url' => $image
                    ]);
                }
            }
            // Add owner to immovable
            $this->setImmovableToOwner($request, $immovable);

            // New insert Notification
            $this->utilsController->registerInmoNotify($immovable);

            DB::commit();

            return $this->successResponseWithMessage('Inmueble agregado correctamente', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function setImmovableToOwner($request, $immovable)
    {
        $user = User::where('id', $request->owner_id)->first();
        $owner = Owner::where('email', $user->email)->first()->id;

        $immovable->update([
            'owner_id' => $owner,
        ]);
        ImmovableOwner::create([
            'immovable_id' => $immovable->id,
            'owner_id' => $owner,
        ]);
    }

    // Immovable Details |Home
    public function showCode($code)
    {
        $immovable = ModelsImmovable::with('details', 'immovableType', 'comformts', 'buildingCompany', 'address', 'coownership', 'details.cuisine', 'details.floor')->where('code', $code)->first();
        $immovable->galleries = Gallery::where('immovable_id', $immovable->id)->where('status', 'accepted')->take(4)->get();
        $immovable->details->makeHidden(['cuisine_type_id', 'floor_type_id']);

        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($immovable);
    }

    public function getImmovable($code)
    {
        $immovable = ModelsImmovable::with('details', 'immovableType', 'comformts', 'address')->where('code', $code)->first();
        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($immovable);
    }


   
}
