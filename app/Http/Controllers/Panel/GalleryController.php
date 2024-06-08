<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Repositories\Admin\GalleryRepository;
use Illuminate\Http\Request;

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

    public function index($id)
    {
        return $this->gallery->getGalleries($id);
    }
}
