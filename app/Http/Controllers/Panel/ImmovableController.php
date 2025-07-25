<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Repositories\Admin\ImmovableRepository;
use App\Http\Resources\Admin\ImmovableAdminResource;
use App\Http\Resources\Inventory\ImmovableOperationResource;
use App\Models\Immovable;
use App\Services\ImmovableService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ImmovableController extends Controller
{

    private ImmovableRepository $immovableRepository;
    private UtilsController $utilsController;

    protected $immovableService;

    public function __construct(ImmovableRepository $immovableRepository, ImmovableService $immovableService)
    {
        $this->immovableRepository = $immovableRepository;
        $this->utilsController = new UtilsController();
        $this->immovableService = $immovableService;
    }
    public function index(): JsonResponse
    {
        $immovables = ImmovableAdminResource::collection(Immovable::orderBy('created_at', 'desc')->get());
        return $this->successResponse($immovables);
    }

    // TODO:: POR CATEGORIA - VENTA, RENTA Y AMBOS.
    public function indexByRent(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::where('category', 'rent')
                    ->where('status', '!=', 'rented')->where('status', '!=', 'retired')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles en renta', Response::HTTP_NOT_FOUND);
        }
    }

    public function indexBySell(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::where('category', 'sale')
                    ->where('status', '!=', 'sold')->where('status', '!=', 'retired')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles en venta', Response::HTTP_NOT_FOUND);
        }
    }
    public function indexByBoth(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::where('category', 'both')
                    ->whereNotIn('status', ['sold', 'rented'])->where('status', '!=', 'retired')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles disponibles para venta y renta', Response::HTTP_NOT_FOUND);
        }
    }
    // TODO:: Inmuebles Rentados - Por Unidad.
    public function indexRentedWithCoOwnership(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::whereIn('category', ['rent', 'both'])
                    ->where('status', 'rented')
                    ->whereNotNull('co_ownership_id')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles rentados', Response::HTTP_NOT_FOUND);
        }
    }
    public function indexSoldWithCoOwnership(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::whereIn('category', ['sale', 'both'])
                    ->where('status', 'sold')
                    ->whereNotNull('co_ownership_id')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles vendidos', Response::HTTP_NOT_FOUND);
        }
    }

    public function indexRented(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::whereIn('category', ['rent', 'both'])
                    ->where('status', 'rented')
                    ->whereNull('co_ownership_id')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles rentados', Response::HTTP_NOT_FOUND);
        }
    }

    public function indexSold(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::whereIn('category', ['sale', 'both'])
                    ->where('status', 'sold')
                    ->whereNull('co_ownership_id')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles vendidos', Response::HTTP_NOT_FOUND);
        }
    }



    // TODO:: POR ESTADO
    public function indexUnderMaintenance(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                $this->immovableService->getByStatus('under_maintenance')
            );
            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles en mantenimiento', Response::HTTP_NOT_FOUND);
        }
    }
    public function indexProcessSale(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::whereIn('category', ['sale', 'both'])
                    ->where('status', 'process_sale')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );
            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles en proceso de venta', Response::HTTP_NOT_FOUND);
        }
    }
    public function indexProcessRenting(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::whereIn('category', ['rent', 'both'])
                    ->where('status', 'process_renting')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles en proceso de renta', Response::HTTP_NOT_FOUND);
        }
    }

    public function indexUnpublished(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::where('status', 'inactive')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles no publicados', Response::HTTP_NOT_FOUND);
        }
    }
    // Dado de baja o retirado
    public function indexRetired(): JsonResponse
    {
        try {
            $immovables = ImmovableAdminResource::collection(
                Immovable::where('status', 'retired')
                    ->orderBy('created_at', 'desc')
                    ->get()
            );

            return $this->successResponse($immovables);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener inmuebles dados de baja', Response::HTTP_NOT_FOUND);
        }
    }

    // Propietario en especifico
    public function indexOwner($id): JsonResponse
    {
        try {
            $immovable =  ImmovableAdminResource::collection(Immovable::where('owner_id', $id)->orderBy('created_at', 'desc')->get());
            return $this->successResponse($immovable);
        } catch (\Exception $e) {
            return $this->errorResponse('Immovables not found', Response::HTTP_NOT_FOUND);
        }
    }



    public function statusChange($id)
    {
        $immovable = Immovable::find($id);
        if (!$immovable) {
            return $this->errorResponse('Immovable not found', Response::HTTP_NOT_FOUND);
        }
        try {
            $status = $immovable->status === 'inactive' ? 'active' : 'inactive';
            $immovable->update([
                'status' => $status,
            ]);
            return $this->successResponseWithMessage('Estado del inmueble actualizado');
        } catch (\Exception $e) {
            return $this->errorResponse('Immovable not found', Response::HTTP_NOT_FOUND);
        }
    }
    // Estados de las imagenes y videos
    public function statusMedia(Request $request, $id)
    {

        $immovable = Immovable::find($id)->with('owner')->first();
        if (!$immovable) {
            return $this->errorResponse('Immovable not found', Response::HTTP_NOT_FOUND);
        }

        $valid = Validator::make($request->all(), [
            'image' => 'required|in:pending,rejected,accepted',
            'video' => 'required|in:pending,rejected,accepted',
        ]);

        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }

        try {
            $immovable->update([
                'video_status' => $request->video,
                'image_status' => $request->image
            ]);
            $this->utilsController->conditionInmoNotify($immovable);
            return $this->successResponseWithMessage('Estados multimedia actualizados');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar los estados multimedia', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Cambiar estado

    public function statusUpdate(Request $request, $id)
    {
        $immovable = Immovable::find($id);
        if (!$immovable) {
            return $this->errorResponse('Immovable not found', Response::HTTP_NOT_FOUND);
        }
        $valid = Validator::make($request->all(), [
            'status' => 'required|in:active,inactive,sold,rented,under_maintenance,process_sale,process_renting',
        ]);

        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }
        try {
            $immovable->update([
                'status' => $request->status,
            ]);
            return $this->successResponseWithMessage('Estado del inmueble actualizado');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al querer actualizar el estado del inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $immovable = Immovable::with('details', 'comformts', 'address', 'coownership.detail')->find($id);
        $immovable->immovableType = $immovable->immovableType()->first()->description;
        $immovable->owner = $immovable->owner()->first();
        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($immovable);
    }

    public function getCoAdminValue($id)
    {
        return $this->immovableRepository->getAdminValue($id);
    }

    public function updateCoAdminValue(Request $request, $id)
    {
        return $this->immovableRepository->updateAdminValue($request, $id);
    }

    public function update(Request $request, $id)
    {
        return $this->immovableRepository->updateImmovable($request, $id);
    }

    public function destroy($id)
    {
        return $this->immovableRepository->deleteImmovable($id);
    }

    public function showUpdate($id)
    {
        return $this->immovableRepository->showUpdate($id);
    }


    # TODO:: ACTUALIZACIONES

    public function updateBasic(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'owner_id' => 'required|exists:owners,id',
            'title' => 'required|max:255',
            'video_url' => 'nullable',
            'image_status' => 'required',
            'video_status' => 'required',
            'immovabletype_id' => 'required|exists:immovabletypes,id',
            'enrollment' => 'nullable|max:255',
            'immonumber' => 'nullable|max:255',
            'stratum' => 'required|min:1|max:6',
            'bedrooms' => 'required|min:1',
            'bathrooms' => 'required|min:1',
            'cuisine_type_id' => 'required|exists:cuisine_types,id',
            'floor_type_id' => 'required|exists:floor_types,id',
            'floor_located' => 'required|min:1',
            'tower' => 'nullable',
            'category' => 'required',
            'description' => 'required',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        // Inmueble
        $immovable = Immovable::find($id)->with('details')->first();

        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }

        DB::beginTransaction();

        try {

            $immovable->update([
                'owner_id' => $request->owner_id,
                'title' => $request->title,
                'video_url' => $request->video_url,
                'image_status' => $request->image_status,
                'video_status' => $request->video_status,
                'immovabletype_id' => $request->immovabletype_id,
                'enrollment' => $request->enrollment,
                'immonumber' => $request->immonumber,
                'category' => $request->category,
                'description' => $request->description,
            ]);

            $immovable->details()->update([
                'bedrooms' => $request->bedrooms,
                'bathrooms' => $request->bathrooms,
                'floor_located' => $request->floor_located,
                'stratum' => $request->stratum,
                'tower' => $request->tower,
                'floor_type_id' => $request->floor_type_id,
                'cuisine_type_id' => $request->cuisine_type_id,
            ]);

            DB::commit();
            return $this->successResponseWithMessage('Inmueble actualizado');
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->errorResponse('Error al actualizar el inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function indexOrderServeice()
    {
        $immovables = ImmovableOperationResource::collection(Immovable::all());
        return $this->successResponse($immovables);
    }
}
