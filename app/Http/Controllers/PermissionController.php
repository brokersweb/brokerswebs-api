<?php

namespace App\Http\Controllers;

use App\Models\Base\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PermissionController extends Controller
{


    public function index()
    {
        $permissions = Permission::all();
        return $this->successResponse($permissions);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:permissions,name',
        ];
        $valid = $this->validate($request, $rules);
        if ($valid) {
            $permission = Permission::create($request->all());
            return $this->successResponse($permission, Response::HTTP_CREATED);
        } else {
            return $this->errorResponse('Error al registrar el permiso', Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $permission = Permission::find($id);
            return $this->successResponse($permission);
        } catch (\Throwable $th) {
            return $this->errorResponse('Permiso no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255|unique:permissions,name,' . $id,
        ];
        $this->validate($request, $rules);
        try {
            $permission = Permission::find($id);
            $permission->update($request->all());
            return $this->successResponse($permission);
        } catch (\Throwable $th) {
            return $this->errorResponse('Permiso no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        try {
            $permission = Permission::find($id);
            if ($permission->roles()->count() > 0) {
                return $this->errorResponse('No se puede eliminar el permiso porque tiene roles asignados', Response::HTTP_BAD_REQUEST);
            }
            $permission->delete();
            return $this->successResponse('Permiso eliminado', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Permiso no encontrado', Response::HTTP_NOT_FOUND);
        }
    }
}
