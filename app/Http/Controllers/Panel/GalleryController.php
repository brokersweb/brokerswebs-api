<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Repositories\Admin\GalleryRepository;
use App\Models\Base\Gallery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GalleryController extends Controller
{
    private GalleryRepository $gallery;

    public function __construct(GalleryRepository $gallery)
    {
        $this->gallery = $gallery;
    }

    public function store(Request $request)
    {
        return $this->gallery->uploadGallery($request);
    }

    public function destroy($id)
    {
        return $this->gallery->deleteGallery($id);
    }

    public function destroyAll($id)
    {
        return $this->gallery->deleteGalleryByImmovable($id);
    }

    public function updateStatus($id)
    {
        return $this->gallery->updateGalleryStatus($id);
    }

    public function show($id)
    {
        return $this->gallery->getGalleries($id);
    }


    public function acceptedAll($id)
    {
        try {
            $galleries = Gallery::where('immovable_id', $id)->get();
            foreach ($galleries as $gallery) {
                $gallery->status = 'accepted';
                $gallery->save();
            }
            return $this->successResponseWithMessage('Imágenes aceptadas correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al aceptar las imágenes', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function rejectedAll($id)
    {
        try {
            $galleries = Gallery::where('immovable_id', $id)->get();
            foreach ($galleries as $gallery) {
                $gallery->status = 'rejected';
                $gallery->save();
            }
            return $this->successResponseWithMessage('Imágenes rechazadas correctamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al rechazar las imágenes', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
