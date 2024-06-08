<?php

namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Http\Resources\ImmovableResource;
use App\Models\Immovable as ModelsImmovable;
use Illuminate\Http\Response;

class ImmovableController extends Controller
{


    public function index()
    {
        $immovables = ImmovableResource::collection(ModelsImmovable::orderBy('created_at', 'desc')
            ->where('image_status', 'accepted')
            ->where('status', 'active')
            ->get());
        return $this->successResponse($immovables);
    }
}
