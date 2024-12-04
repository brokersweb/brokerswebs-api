<?php

namespace App\Http\Controllers;

use App\Http\Repositories\Admin\OwnerRepository;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private OwnerRepository $ownerRepository;

    public function __construct(OwnerRepository $ownerRepository)
    {
        $this->ownerRepository = $ownerRepository;
    }

    public function index()
    {
        $users = User::all()->load('roles');
        // dd($users);
        return $this->successResponse($users);
    }

    public function show($id)
    {
        try {
            $user = User::find($id)->load('roles');
            return $this->successResponse($user);
        } catch (\Throwable $th) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'cellphone' => 'required|unique:users,cellphone,' . $id,
            'phone' => 'unique:users,phone,' . $id,
            'address' => 'max:255',
            'birthday' => 'required',
            'photo' => 'nullable',
        ];
        $this->validate($request, $rules);
        try {
            $user = User::find($id);
            $user->update($request->all());
            return $this->successResponse($user);
        } catch (\Throwable $th) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
    }
    public function assignRole(Request $request, $id)
    {
        $rules = [
            'role_id' => 'required|exists:roles,id',
        ];
        $this->validate($request, $rules);
        // try {
        $user = User::find($id);

        if ($user->hasRole($request->role_id)) {
            return $this->errorResponse(
                'El usuario ya tiene el rol asignado',
                Response::HTTP_CONFLICT
            );
        }

        DB::beginTransaction();
        try {

            $user->assignRole($request->role_id);
            DB::commit();

            // $user->assignRole($request->role_id);
            return $this->successResponseWithMessage('Rol asignado correctamente');
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->errorResponse('Error al asignar el rol', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateRoles(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
        $rules = [
            'roles' => 'nullable|exists:roles,id'
        ];
        $this->validate($request, $rules);
        DB::beginTransaction();
        try {
            if ($request->roles == []) {
                $user->roles()->detach();
                $this->ownerRepository->storeOwnerRole($user->id);
                DB::commit();
                return $this->successResponseWithMessage('Roles Eliminados');
            } else {
                $user->roles()->detach();
                $user->assignRole($request->roles);
                if ($user->roles->count() == 1 && $user->hasRole('Lessor')) {
                    $this->ownerRepository->storeOwnerRole($user->id);
                }
                DB::commit();
                return $this->successResponseWithMessage('Roles actualizados');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return $this->errorResponse('Error al actualizar el rol', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changeStatus($id)
    {
        try {
            $user = User::find($id);
            $user->status = $user->status == 'active' ? 'inactive' : 'active';
            $user->update();
            return $this->successResponse($user);
        } catch (\Throwable $th) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);
            $user->roles()->detach();
            $user->delete();
            return $this->successResponse('Usuario eliminado', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
        $rules = [
            'oldpassword' => 'required|min:8',
            'newpassword' => 'required|min:8',
            'newpassword_confirmation' => 'required|same:newpassword|min:8',
        ];
        $this->validate($request, $rules);
        // Checked passwords
        $checkPassw = Hash::check($request->oldpassword, $user->password);
        if (!$checkPassw) {
            return $this->errorResponse('Contraseña incorrecta', Response::HTTP_NOT_FOUND);
        }
        try {
            $user->update([
                'password' => Hash::make($request->newpassword)
            ]);
            return $this->successResponse($user);
        } catch (\Throwable $th) {
            return $this->errorResponse('Error al actualizar la contraseña', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }



    public function indexStaff()
    {
        $users = User::select('id', 'name', 'lastname')->whereHas('roles', function ($query) {
            $query->where('name', 'Staff');
        })->get();
        return $this->successResponse($users);
    }
}
