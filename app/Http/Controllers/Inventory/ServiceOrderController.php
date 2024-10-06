<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\OrderServiceResource;
use App\Models\Immovable;
use App\Models\Inventory\ServiceOrder;
use App\Traits\AuthorizesRoleOrPermission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ServiceOrderController extends Controller
{
    use AuthorizesRoleOrPermission;

    public function index()
    {
        $orders = OrderServiceResource::collection(ServiceOrder::orderBy('created_at', 'desc')->get());
        return $this->successResponse($orders);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'assigned_id' => 'required',
            'client_id' => 'required',
            'comment' => 'nullable',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'location' => 'nullable',
            'details' => 'required|array'
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {
            // Orden
            $start_time = Carbon::createFromFormat('g:i A', $request->start_time);

            $order = ServiceOrder::create([
                'user_id' => Auth::id(),
                'assigned_id' => $request->assigned_id,
                'client_id' => $request->client_id,
                'client_type' => Immovable::class,
                'comment' => $request->comment,
                'start_date' => $request->start_date,
                'start_time' => $start_time->format('H:i:s'),
                'location' => $request->location,
            ]);

            foreach ($request->details as $service) {
                $order->services()->create([
                    'description' => $service['name'],
                    'qty' => 1,
                    'price' => $service['price'],
                ]);
            }

            DB::commit();

            return $this->successResponseWithMessage('Orden creada exitosamente', Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al crear la orden', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
