<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\MaterialResource;
use App\Models\Inventory\Material;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MaterialImport;
use App\Imports\MaterialPriceImport;
use App\Services\LowStockReportService;
use App\Traits\AuthorizesRoleOrPermission;

class MaterialController extends Controller
{
    use AuthorizesRoleOrPermission;

    private $lowStockReportService;

    public function __construct(LowStockReportService $lowStockReportService)
    {
        $this->lowStockReportService = $lowStockReportService;
    }

    /**
     * @OA\Get(
     *     path="/api/panel/inventory/materials",
     *     tags={"Materiales"},
     *     summary="Obtener todas los materiales",
     *     security={{"bearerAuth":{}}},
     *     operationId="materials",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function index()
    {
        $materials = MaterialResource::collection(Material::orderBy('created_at', 'desc')->get());
        return $this->successResponse($materials);
    }

    /**
     * @OA\Get(
     *     path="/api/inventory/materials/options",
     *     tags={"Materiales"},
     *     summary="Materiales de los select options",
     *     @OA\Response(
     *         response="default",
     *         description="successful operation"
     *     )
     * )
     */
    public function indexOptions()
    {
        $materials = Material::select('id', 'name', 'code')
            ->where('stock', '>', 0)->where('status', 'available')
            ->orderBy('created_at', 'desc')->get();
        return $this->successResponse($materials);
    }

    public function indexToSale()
    {
        $materials = Material::select('id', 'name', 'code', 'photo', 'stock', 'price_basic')
            ->where('stock', '>', 0)->where('status', 'available')
            ->orderBy('created_at', 'desc')->get();
        return $this->successResponse($materials);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:materials,code',
            'stock' => 'required|integer',
            'unit' => 'nullable',
            'price_basic' => 'nullable',
            'photo' => 'nullable',
            'category_id' => 'required|exists,categories,id',
            'operative_status' => 'nullable',
            'conditions' => 'required',
        ]);

        $request['status'] = 'available';
        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $material = Material::create($request->all());

        return $this->successResponseWithMessage('Material agregado exitosamente', Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $material = Material::find($id);
            return $this->successResponse($material);
        } catch (\Throwable $th) {
            return $this->errorResponse('Material no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'stock' => 'required',
            'price_basic' => 'nullable',
            'photo' => 'nullable',
            'category_id' => 'required|exists,categories,id',
            'operative_status' => 'nullable',
            'conditions' => 'required',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $material = Material::find($id);
            $material->update([
                'name' => $request->name,
                'stock' => $request->stock,
                'price_basic' => $request->price_basic,
                'photo' => $request->photo,
            ]);
            return $this->successResponseWithMessage('Material actualizado exitosamente', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Material no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        $this->authorizeRoleOrPermission('Administrator');

        try {
            $material = Material::find($id);
            $material->delete();
            return $this->successResponseWithMessage('Material eliminado', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Material no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function import(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        if ($request->file('file')) {
            try {
                Excel::import(new MaterialImport, $request->file('file'));
                return $this->successResponseWithMessage('Materiales importados exitosamente', Response::HTTP_OK);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->errorResponse('No se ha seleccionado un archivo', Response::HTTP_BAD_REQUEST);
    }

    // TODO:: PRECIOS
    public function updatePrice(Request $request, $id)
    {

        $material = Material::find($id);

        if (!$material) {
            return $this->errorResponse('Material no encontrado', Response::HTTP_NOT_FOUND);
        }


        $valid = Validator::make($request->all(), [
            'price' => 'required',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            $material->update([
                'price_basic' => $request->price
            ]);
            return $this->successResponseWithMessage('Precio actualizado exitosamente', Response::HTTP_OK);
        } catch (\InvalidArgumentException $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_BAD_REQUEST);
        }
    }

    public function importPrice(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        if ($request->file('file')) {
            try {
                Excel::import(new MaterialPriceImport, $request->file('file'));
                return $this->successResponseWithMessage('Precios actualizados exitosamente', Response::HTTP_OK);
            } catch (\Exception $e) {
                return $this->errorResponse('Ocurrió un error al actualizar los precios', Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->errorResponse('No se ha seleccionado un archivo', Response::HTTP_BAD_REQUEST);
    }



    public function generateLowStockReport(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {

            $result = $this->lowStockReportService->generateLowStockReport(10, $request->email);

            return response()->json([
                'success' => $result,
                'message' => $result
                    ? 'Reporte de stock bajo generado y enviado'
                    : 'No hay materiales con stock bajo'
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error al generar el reporte', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


}
