<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\ToolResource;
use App\Imports\ToolImport;
use App\Models\Inventory\Tool;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ToolsImport;
use App\Traits\AuthorizesRoleOrPermission;

class ToolController extends Controller
{
    use AuthorizesRoleOrPermission;

    public function index()
    {
        $tools = ToolResource::collection(Tool::orderBy('created_at', 'desc')->get());
        return $this->successResponse($tools);
    }

    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required|unique:tools,code',
            'total_quantity' => 'required|integer',
            'price' => 'nullable|numeric',
            'photo' => 'nullable',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tool = Tool::create([
            'name' => $request->name,
            'code' => $request->code,
            'total_quantity' => $request->total_quantity,
            'available_quantity' => $request->total_quantity,
            'price' => $request->price,
            'photo' => $request->photo,
        ]);

        return $this->successResponseWithMessage('Herramienta agregada exitosamente', Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $tool = Tool::find($id);
            return $this->successResponse($tool);
        } catch (\Throwable $th) {
            return $this->errorResponse('Herramienta no encontrada', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required',
            'total_quantity' => 'required|integer',
            'price' => 'nullable|numeric',
            'photo' => 'nullable',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tool = Tool::find($id);

        $tool->update([
            'name' => $request->name,
            'total_quantity' => $request->total_quantity,
            'price' => $request->price,
            'photo' => $request->photo,
        ]);

        return $this->successResponseWithMessage('Herramienta actualizada exitosamente', Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $this->authorizeRoleOrPermission('Administrator');

        $tool = Tool::find($id);
        $tool->delete();
        return $this->successResponseWithMessage('Herramienta eliminada exitosamente', Response::HTTP_OK);
    }


    // Herramientas Disponibles para prestamo
    public function availableTools()
    {
        $tools = Tool::select('id', 'code', 'name')->where('available_quantity', '>', 0)->get();
        return $this->successResponse($tools);
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
                Excel::import(new ToolImport, $request->file('file'));
                return $this->successResponseWithMessage('Herramientas importadas exitosamente', Response::HTTP_OK);
            } catch (\Exception $e) {
                return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        return $this->errorResponse('No se ha seleccionado un archivo', Response::HTTP_BAD_REQUEST);
    }
}
