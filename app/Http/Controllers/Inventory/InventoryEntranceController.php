<?php


namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\EntranceResource;
use App\Imports\EntranceImport;
use App\Models\Inventory\InventoryEntrance;
use App\Models\Inventory\InventoryEntranceItem;
use App\Models\Inventory\Material;
use App\Models\Inventory\Tool;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class InventoryEntranceController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/panel/inventory/entries",
     *     tags={"Ingresos o Entradas"},
     *     summary="Obtener todas las entradas",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {

        $entrances = EntranceResource::collection(InventoryEntrance::orderBy('created_at', 'desc')->get());
        return $this->successResponse($entrances);
    }

    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'supplier_id' => 'required|exists:suppliers,id',
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {

            $entrance = InventoryEntrance::create([
                'user_id' => Auth::id(),
                'supplier_id' => $request->supplier_id,
            ]);

            Excel::import(new EntranceImport, $request->file('file'));

            DB::commit();

            return $this->successResponseWithMessage('Ingreso creado exitosamente', Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function updateStatu(Request $request, $id)
    {
        $entrance = InventoryEntrance::find($id);

        if (!$entrance) {
            return $this->errorResponse('No existe el ingreso', Response::HTTP_NOT_FOUND);
        }

        $valid = Validator::make($request->all(), [
            'status' => 'required',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $request->status == 1  ? $entrance->status = 'confirmed' : $entrance->status = 'cancelled';
        $entrance->save();


        if ($entrance->status == 'confirmed') {
            $this->confirmedEntrance($entrance);
        } else {
            $this->cancelledEntrance($entrance);
        }
    }


    public function confirmedEntrance($entrance)
    {

        $items = InventoryEntranceItem::where('inventory_entrance_id', $entrance->id)->get();

        try {
            foreach ($items as $item) {
                // TODO:: Materiales
                if ($item->type == 1) {
                    // TODO:: Material Existente / Nuevo
                    $material = Material::where('code', $item->code)->first();

                    if ($material) {
                        $material->update([
                            'stock' => $material->stock + $item->qty,
                            'status' => 'available'
                        ]);
                    } else {
                        $mate = Material::create([
                            'name' => $item->name,
                            'code' => $item->code,
                            'price_basic' => $item->price,
                        ]);
                    }
                }
                // TODO:: Herramientas
                if ($item->type == 2) {
                    // TODO:: Existente / Nuevo
                    $tool = Tool::where('code', $item->code)->first();
                    if ($tool) {
                        $tool->update([
                            'total_quantity' => $tool->total_quantity + $item->qty,
                            'available_quantity' => $tool->available_quantity + $item->qty,
                            'status' => 'available'
                        ]);
                    } else {
                        $tol = Tool::create([
                            'name' => $item->name,
                            'code' => $item->code,
                            'total_quantity' => $item->qty,
                            'price' => $item->price,
                            'available_quantity' => $item->qty,
                        ]);
                    }
                }
            }

            $entrance->update([
                'status' => 'confirmed',
                'confirmed_at' => Carbon::now(),
                'confirmed_by' => Auth::id()
            ]);

            return $this->successResponse('Entrada confirmada exitosamente', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al confirmar la entrada', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function cancelledEntrance($entrance)
    {

        $items = InventoryEntranceItem::where('inventory_entrance_id', $entrance->id)->get();

        try {
            foreach ($items as $item) {
                // TODO:: Materiales
                if ($item->type == 1) {
                    // TODO:: Material Existente / Nuevo
                    $material = Material::where('code', $item->code)->first();

                    if ($material && $material->stock >= $item->qty) {
                        $material->update([
                            'stock' => $material->stock - $item->qty,
                        ]);

                        if ($material->stock == 0) {
                            $material->update([
                                'stock' => 0,
                                'status' => 'out_stock'
                            ]);
                        }
                    }
                }
                // TODO:: Herramientas
                if ($item->type == 2) {
                    // TODO:: Existente / Nuevo
                    $tool = Tool::where('code', $item->code)->first();
                    if ($tool && ($tool->total_quantity >= $item->qty)) {
                        $tool->update([
                            'total_quantity' => $tool->total_quantity - $item->qty,
                            'available_quantity' => $tool->available_quantity - $item->qty,
                        ]);

                        if ($tool->total_quantity == 0) {
                            $tool->update([
                                'total_quantity' => 0,
                                'available_quantity' => 0,
                                'status' => 'out_stock'
                            ]);
                        }
                    }
                }
            }

            $entrance->update([
                'status' => 'cancelled',
                'cancelled_at' => Carbon::now(),
                'cancelled_by' => Auth::id()
            ]);

            return $this->successResponse('Entrada cancelada exitosamente', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al confirmar la entrada', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {
            $entrance = InventoryEntrance::find($id);
            if (!$entrance) {
                return $this->errorResponse('Entrada no encontrada', Response::HTTP_NOT_FOUND);
            }
            $entrance->delete();
            return $this->successResponse(
                'Entrada eliminada exitosamente',
                Response::HTTP_OK
            );
        } catch (\Exception $e) {
            return $this->errorResponse(
                'Error al eliminar la entrada',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
