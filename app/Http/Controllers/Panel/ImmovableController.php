<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Repositories\Admin\ImmovableRepository;
use App\Http\Resources\Admin\ImmovableAdminResource;
use App\Models\Immovable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImmovableController extends Controller
{

    private ImmovableRepository $immovableRepository;
    private UtilsController $utilsController;

    public function __construct(ImmovableRepository $immovableRepository)
    {
        $this->immovableRepository = $immovableRepository;
        $this->utilsController = new UtilsController();
    }
    public function index(): JsonResponse
    {
        $immovables = ImmovableAdminResource::collection(Immovable::orderBy('created_at', 'desc')->get());
        return $this->successResponse($immovables);
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

    // Solo inmuebles rentados
    public function indexRented(): JsonResponse
    {
        try {
            $immovable =  ImmovableAdminResource::collection(Immovable::where('status', 'rented')->orderBy('created_at', 'desc')->get());
            return $this->successResponse($immovable);
        } catch (\Exception $e) {
            return $this->errorResponse('Immovables not found', Response::HTTP_NOT_FOUND);
        }
    }

    // Solo inmuebles vendidos
    public function indexSold(): JsonResponse
    {
        try {
            $immovable =  ImmovableAdminResource::collection(Immovable::where('status', 'sold')->orderBy('created_at', 'desc')->get());
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
}
