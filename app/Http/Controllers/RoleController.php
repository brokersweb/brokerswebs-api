<?php

namespace App\Http\Controllers;

use App\Http\Resources\Admin\RoleResource;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    public function index()
    {
        $roles = RoleResource::collection(Role::orderBy('name', 'asc')->get());
        return $this->successResponseWithout($roles);
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:roles,name',
        ];
        $valid = $this->validate($request, $rules);
        if ($valid) {
            $role = Role::create($request->all());
            return $this->successResponse($role, Response::HTTP_CREATED);
        } else {
            return $this->errorResponse('Error al registrar el rol', Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        try {
            $role = Role::find($id)->load('permissions');
            return $this->successResponse($role);
        } catch (\Throwable $th) {
            return $this->errorResponse('Rol no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $role = Role::find($id);
            $role->update($request->all());
            return $this->successResponse($role);
        } catch (\Throwable $th) {
            return $this->errorResponse('Rol no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        try {
            $role = Role::find($id);
            $role->permissions()->detach();
            $role->delete();
            return $this->successResponse('Rol eliminado', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Rol no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    // Assign Permissions
    public function assignPermissions(Request $request, $id)
    {
        $rules = [
            'permissions' => 'required|array|exists:permissions,id',
        ];
        $this->validate($request, $rules);
        try {
            $role = Role::find($id);
            $role->syncPermissions($request->permissions);
            return $this->successResponse('Permisos asignados', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Rol no encontrado', Response::HTTP_NOT_FOUND);
        }
    }
    // Sync Permissions
    public function updatePermissions(Request $request, $id)
    {
        $rules = [
            'permissions' => 'required|array|exists:permissions,id',
        ];
        $this->validate($request, $rules);
        try {
            $role = Role::find($id);
            // $permissions_now = $role->permissions->pluck('id')->toArray();
            $role->syncPermissions($request->permissions);
            $role->update();
            return $this->successResponse('Permisos actualizados', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Rol no encontrado', Response::HTTP_NOT_FOUND);
        }
    }
}
