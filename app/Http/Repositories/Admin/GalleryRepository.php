<?php

namespace App\Http\Repositories\Admin;

use App\Models\Base\Gallery;
use App\Models\Immovable;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class GalleryRepository extends Repository
{
    use ApiResponse;

    private $model;

    public function __construct()
    {
        $this->model = new Gallery();
    }


    public function getGalleries($id)
    {
        $galleries = Gallery::where('immovable_id', $id)->orderBy('created_at', 'desc')->paginate(5);

        try {
            if (!$galleries) {
                return $this->errorResponse('Galleries not found', Response::HTTP_NOT_FOUND);
            }
            return $this->successResponse($galleries);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener las imágen', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function uploadGallery($request)
    {
        $rules = Validator::make($request->all(), [
            'images' => 'required',
            'immovable_id' => 'required|exists:immovables,id',
        ]);

        if ($rules->fails()) {
            return $this->errorResponse($rules->errors(), Response::HTTP_BAD_REQUEST);
        }

        $immovable = Immovable::find($request->immovable_id);

        if (!$immovable) {
            return $this->errorResponse('Immovable not found', Response::HTTP_NOT_FOUND);
        }

        try {
            if (!is_null($request->images)) {
                foreach ($request->images as $image) {
                    $immovable->galleries()->create([
                        'url' => $image
                    ]);
                }
                return $this->successResponseWithMessage('¡Imagen guardada exitosamente!');
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Error al agregagar la imágen', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function deleteGallery($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return $this->errorResponse('Gallery not found', Response::HTTP_NOT_FOUND);
        }
        try {
            $gallery->delete();
            return $this->successResponseWithMessage('¡Imágen eliminada exitosamente!');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al eliminar la imágen', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function deleteGalleryByImmovable($id)
    {
        $gallery = Gallery::where('immovable_id', $id)->get();
        if (!$gallery) {
            return $this->errorResponse('Gallery not found', Response::HTTP_NOT_FOUND);
        }
        try {
            foreach ($gallery as $item) {
                $item->delete();
            }
            return $this->successResponseWithMessage('¡Imágenes eliminadas exitosamente!');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al eliminar las imágen', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateGalleryStatus($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return $this->errorResponse('Gallery not found', Response::HTTP_NOT_FOUND);
        }
        try {
            switch ($gallery->status) {
                case 'accepted':
                    $gallery->update([
                        'status' => 'rejected',
                    ]);
                    break;
                case 'rejected':
                    $gallery->update([
                        'status' => 'accepted',
                    ]);
                    break;
                case 'pending':
                    $gallery->update([
                        'status' => 'accepted',
                    ]);
                    break;
            }
            return $this->successResponseWithMessage('¡Imágen actualizada exitosamente!');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar la imágen', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
