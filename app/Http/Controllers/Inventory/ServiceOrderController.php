<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\Material\MaterialResource;
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
use App\Models\Inventory\InventoryClient;
use App\Models\Inventory\InventoryConsumableMaterial;
use App\Models\Inventory\InventoryImage;
use App\Models\Inventory\Material;
use App\Models\Inventory\ServiceOrderDetail;

class ServiceOrderController extends Controller
{
    use AuthorizesRoleOrPermission;

    /**
     * @OA\Get(
     *     path="/api/panel/inventory/order-services/",
     *     tags={"Orden de servicio"},
     *     summary="Obtener todas las ordenes de servicio",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $orders = OrderServiceResource::collection(ServiceOrder::orderBy('created_at', 'desc')->get());
        return $this->successResponse($orders);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'assigned_id' => 'required',
            'client_type' => 'required|in:1,2',
            'client_id' => 'required_if:client_type,2',
            'immovable_id' => 'required_if:client_type,1',
            'descrequest' => 'required',
            'comment' => 'nullable',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'evidences' => 'nullable|array',
            'tenant_id' => 'nullable'
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }


        DB::beginTransaction();

        try {

            $start_time = Carbon::createFromFormat('g:i A', $request->start_time);

            $order = ServiceOrder::create([
                'user_id' => Auth::id(),
                'assigned_id' => $request->assigned_id,
                'client_id' => $request->client_type == 1 ? $request->immovable_id : $request->client_id,
                'client_type' => $request->client_type == 1 ? Immovable::class : InventoryClient::class,
                'type' =>  $request->client_type == 1 ? 'int' : 'ext',
                'request' => $request->descrequest,
                'tenant_id' => $request->tenant_id,
                'notes' => $request->comment,
                'start_date' => $request->start_date,
                'start_time' => $start_time->format('H:i:s'),
            ]);

            if ($request->has('evidences')) {
                foreach ($request->evidences as $evidence) {
                    $order->evidences()->create([
                        'url' => $evidence,
                        'situation' => 'damage'
                    ]);
                }
            }

            DB::commit();

            return $this->successResponseWithMessage('Orden de servicio creada exitosamente', Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al crear la orden de servicio', Response::HTTP_INTERNAL_SERVER_ERROR);
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

    // TODO:: Cotizar Serivicios
    public function getOrderServices($id)
    {
        $order = ServiceOrder::find($id)->load('services', 'consumes');
        if (!$order) {
            return $this->errorResponse('Orden no encontrada', Response::HTTP_NOT_FOUND);
        }

        try {

            return $this->successResponse([
                'id' => $order->id,
                'code' => $order->code,
                'assigned_id' => $order->assigned_id,
                'progress' => $order->progress,
                'services' => $order->services,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al obtener la orden',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }


    public function getOrderMaterials($id)
    {
        $order = ServiceOrder::find($id)->load('consumes.material');
        if (!$order) {
            return $this->errorResponse('Orden no encontrada', Response::HTTP_NOT_FOUND);
        }

        try {

            return $this->successResponse([
                'id' => $order->id,
                'code' => $order->code,
                'assigned_id' => $order->assigned_id,
                'progress' => $order->progress,
                'materials' => MaterialResource::collection($order->consumes),
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
            'details' => 'required|array',
            'notes' => 'nullable',
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

            if ($request->has('notes')) {
                $newNote = $request->notes;
                $order->notes = $order->notes
                    ? $order->notes . "\n ." . $newNote
                    : $newNote;
            }

            $order->save();

            return $this->successResponseWithMessage('Servicios agregados exitosamente');
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

    // TODO:: ------------------------- Agregar consumo de materiales --------------------------------
    public function StoreConsumeMaterials(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'order_id' => 'required|exists:service_orders,id',
            'details.*.matId' => 'required|exists:materials,id',
            'notes' => 'nullable',
            'progress' => 'required'
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {

            $order = ServiceOrder::find($request->order_id);
            if (!$order) {
                return $this->errorResponse('Orden de servicio no encontrada', Response::HTTP_NOT_FOUND);
            }

            foreach ($request->details as $detail) {
                $materi = InventoryConsumableMaterial::create([
                    'service_order_id' => $order->id,
                    'material_type' => Material::class,
                    'material_id' => $detail['matId'],
                    'qty' => $detail['qty'],
                ]);
            }

            if ($request->has('notes')) {
                $newNote = $request->notes;
                $order->notes = $order->notes
                    ? $order->notes . "\n ." . $newNote
                    : $newNote;
            }

            $order->progress = $request->progress;
            $order->save();

            return $this->successResponseWithMessage('Materiales agregados exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al consumir materiales', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function removeConsume($id)
    {
        $service = InventoryConsumableMaterial::find($id);
        if (!$service) {
            return $this->errorResponse('Material no encontrado', Response::HTTP_NOT_FOUND);
        }

        try {
            $service->delete();
            return $this->successResponseWithMessage('Material eliminado de manera exitosa', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al eliminar el material',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
