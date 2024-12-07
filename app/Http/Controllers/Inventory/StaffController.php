<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use App\Models\User;
use App\Models\User\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    public function index()
    {
        $staff = User::orderBy('created_at', 'DESC')->whereHas('roles', function ($query) {
            $query->where('name', 'Staff');
        })->get();

        return $this->successResponse($staff);
    }
    // Todo:: Construcción
    public function indexConstruccion()
    {
        $staff = User::orderBy('created_at', 'DESC')->where('payroll', 'building')
            ->where('status', 'active')->whereHas('roles', function ($query) {
                $query->where('name', 'Staff');
            })->get();

        return $this->successResponse($staff);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'dni' => 'required|min:7',
            'email' => 'required|email|unique:users,email',
            'cellphone' => 'required|unique:users,cellphone',
            'photo' => 'required',
            'birthday' => 'nullable',
            'entity' => 'required|in:nequi,bank',
            'holder_name' => 'required',
            'payroll' => 'required',
            'bank' => 'required_if:entity,bank',
            'type' => 'required_if:entity,bank',
            'account_number' => 'required'
        ]);


        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'email' => $request->email,
                'cellphone' => $request->cellphone,
                'payroll' => $request->payroll,
                'password' =>  Hash::make('Password123'),
                'dni' => $request->dni,
                'status' => 'active',
                'photo' => $request->photo
            ]);

            // Bank account
            BankAccount::create([
                'ownerable_id' => $user->id,
                'ownerable_type' => User::class,
                'entity' => $request->entity,
                'holder_name' => base64_encode($request->holder_name),
                'bank' => base64_encode($request->bank),
                'type' => base64_encode($request->type),
                'account_number' => base64_encode($request->account_number),
            ]);

            $role = Role::where('name', 'Staff')->first();
            $user->roles()->attach($role->id);

            DB::commit();
            return $this->successResponseWithMessage('Personal registrado correctamente, ahora puedes navegar e interactuar en el sistema.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al registrar el personal', Response::HTTP_BAD_REQUEST);
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('El Personal no existe', Response::HTTP_NOT_FOUND);
        }

        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'dni' => 'required|min:7',
            'email' => 'required|email|unique:users,email,' . $id,
            'cellphone' => 'required|unique:users,cellphone,' . $id,
            'photo' => 'required',
            'payroll' => 'required',
            'birthday' => 'nullable',
            'status' => 'required'
        ]);


        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {

            $nameParts = explode(' ', $request->name);

            $user->update([
                'name' => $nameParts[0],
                'lastname' => implode(" ", array_slice($nameParts, 1)),
                'email' => $request->email,
                'cellphone' => $request->cellphone,
                'dni' => $request->dni,
                'payroll' => $request->payroll,
                'status' => $request->status,
                'photo' => $request->photo
            ]);

            return $this->successResponseWithMessage('Personal actualizado correctamente, ahora puedes navegar e interactuar en el sistema.', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al actualizar el personal', Response::HTTP_BAD_REQUEST);
        }
    }

    public function show($id)
    {
        $user = User::find($id)->load('bankaccount');
        if (!$user) {
            return $this->errorResponse('El Personal no existe', Response::HTTP_NOT_FOUND);
        }
        try {
            return $this->successResponse($user);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener el personal', Response::HTTP_BAD_REQUEST);
        }
    }


    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->errorResponse('El Personal no existe', Response::HTTP_NOT_FOUND);
        }
        try {
            $banka = BankAccount::find($id);
            if ($banka) {
                $banka->delete();
            }
            $user->delete();
            return $this->successResponseWithMessage('El reistro del personal ha sido eliminado con éxito');
        } catch (\Exception $e) {
            return $this->errorResponse('Error al obtener el personal', Response::HTTP_BAD_REQUEST);
        }
    }
}
