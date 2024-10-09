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
use App\Http\Resources\Inventory\Orders\OrderServiceResource as OrderOneServiceResource;
use App\Models\Inventory\ServiceOrderDetail;

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


    public function showDetails($id)
    {
        $order = ServiceOrder::find($id)->load('user', 'staff', 'services', 'client');

        if (!$order) {
            return $this->errorResponse('Orden no encontrada', Response::HTTP_NOT_FOUND);
        }

        try {

            return $this->successResponse(new OrderOneServiceResource($order), Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al obtener la orden',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function show($id)
    {
        $order = ServiceOrder::find($id);

        if (!$order) {
            return $this->errorResponse('Orden no encontrada', Response::HTTP_NOT_FOUND);
        }

        try {
            return $this->successResponse($order, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al obtener la orden',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    // TODO:: Serivicios
    public function getOrderServices($id)
    {
        $order = ServiceOrder::find($id)->load('services');
        if (!$order) {
            return $this->errorResponse('Orden no encontrada', Response::HTTP_NOT_FOUND);
        }

        try {

            return $this->successResponse([
                'id' => $order->id,
                'code' => $order->code,
                'services' => $order->services,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al obtener la orden',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function storeOrderService(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'order_id' => 'required|exists:service_orders,id',
            'details' => 'required|array'
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $order = ServiceOrder::find($request->order_id);
            foreach ($request->details as $detail) {
                $order->services()->create([
                    'description' => $detail['name'],
                    'qty' => 1,
                    'price' => $detail['price'],
                ]);
            }
            return $this->successResponseWithMessage('Servicios agregados exitosamente', Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function removeService($id)
    {
        $service = ServiceOrderDetail::find($id);
        if (!$service) {
            return $this->errorResponse('Servicio no encontrado', Response::HTTP_NOT_FOUND);
        }

        try {
            $service->delete();
            return $this->successResponseWithMessage('Servicio eliminado de manera exitosa', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al eliminar el servicio',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
