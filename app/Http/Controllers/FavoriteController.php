<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImmovableResource;
use App\Models\Base\Favorite;
use App\Models\Immovable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/favorites",
     *     tags={"Favorites"},
     *     summary="Obtener todos los favoritos",
     *     operationId="favorites",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $favorites = Favorite::all();
        return $this->successResponse($favorites);
    }

    public function indexByUser($userId)
    {
        $immovables = ImmovableResource::collection(Immovable::orderBy('created_at', 'desc')
            ->where('image_status', 'accepted')
            ->where('status', 'active')
            ->whereHas('favorites', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->get());
        return $this->successResponse($immovables);
    }

    /**
     * @OA\Get(
     *     path="/api/favorites/{id}",
     *     tags={"Favorites"},
     *     summary="Obtener un favorito",
     *     operationId="favorites/{id}",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del favorito",
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

    public function immovableStore(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'modelable_id' => 'required|exists:immovables,id',
        ];

        $this->validate($request, $rules);

        try {

            $favorite = Favorite::where('user_id', $request->user_id)
                ->where('modelable_id', $request->modelable_id)
                ->where('modelable_type', Immovable::class)
                ->first();

            if ($favorite) {
                $favorite->delete();
                return $this->successResponseWithMessage('Inmueble eliminado de favoritos');
            } else {
                Favorite::create([
                    'user_id' => $request->user_id,
                    'modelable_id' => $request->modelable_id,
                    'modelable_type' => Immovable::class,
                ]);
                return $this->successResponseWithMessage('Inmueble agregado a favoritos');
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
