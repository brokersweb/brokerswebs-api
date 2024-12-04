<?php

namespace App\Http\Repositories\Admin;

use App\Http\Resources\Admin\Owner\OwnerResource;
use App\Models\Owner;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class OwnerRepository extends Repository
{
    use ApiResponse;
    private $model, $usermodel;

    public function __construct()
    {
        $this->model = new Owner();
        $this->usermodel = new User();
    }

    public function all()
    {
        $owners = OwnerResource::collection($this->model->orderBy('created_at', 'desc')->get());
        return response()->json($owners, Response::HTTP_OK);
    }

    public function getOwner($id)
    {
        $owner = $this->model->find($id);
        try {
            if ($owner) {
                return $this->successResponse($owner);
            } else {
                return $this->errorResponse('No se encontró el propietario', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el propietario', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOwnerByUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
        $owner = $this->model->where('email', $user->email)->first();
        try {
            if ($owner) {
                return $this->successResponse($owner);
            } else {
                return $this->errorResponse('No se encontró el propietario', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el propietario', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create($request)
    {
        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'document_type' => 'nullable|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'dni' => 'nullable|max:25',
            'expedition_place' => 'nullable|max:100',
            'expedition_date' => 'nullable|date',
            'email' => 'required|email',
            'cellphone' => 'required|unique:owners,cellphone',
            'phone' => 'nullable|unique:owners,phone',
            'address' => 'nullable|max:255',
            'holder_name' => 'nullable',
            'bank_name' => 'nullable',
            'account_type' => 'nullable|in:Ahorros,Corriente',
            'account_number' => 'nullable|integer',
            'birthdate' => 'required|date',
            'gender' => 'nullable|in:Masculino,Femenino,Otro',
            'civil_status' => 'nullable|in:Soltero,Casado,Unión libre,Viudo,Divorciado',
            'dependent_people' => 'nullable',
            'profession' => 'nullable',
            'dni_file' => 'nullable',
            'rut' => 'nullable',
            'nit' => 'nullable',
            'photo' => 'nullable',
        ]);

        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }

        $bank_account = array(
            'holder_name' => base64_encode($request->holder_name),
            'bank_name' => base64_encode($request->bank_name),
            'account_type' => base64_encode($request->account_type),
            'account_number' => base64_encode($request->account_number),
        );

        Owner::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'document_type' => $request->document_type,
            'dni' => $request->dni,
            'rut' => $request->rut,
            'nit' => $request->nit,
            'expedition_place' => $request->expedition_place,
            'expedition_date' => $request->expedition_date,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'bank_account' => json_encode($bank_account),
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'dependent_people' => $request->dependent_people,
            'profession' => $request->profession,
            'dni_file' => $request->dni_file,
            'photo' => $request->photo,
            'type' => 'holder',
        ]);

        // Create User Profile
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'birthday' => $request->birthdate,
            'dni' => $request->dni,
            'status' => 'Active',
            'password' => bcrypt('Password123*'),
        ]);


        return $this->successResponseWithMessage('Propietario registrado de manera exitosa.');
    }

    // Actualizar el perfil del propietario
    public function update($request, $id)
    {
        $owner = $this->model->find($id);
        if (!$owner) {
            return $this->errorResponse('No se encontró el propietario', Response::HTTP_NOT_FOUND);
        }
        $valid = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'document_type' => 'required|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'dni' => 'required|max:25',
            'expedition_place' => 'required|max:100',
            'expedition_date' => 'required|date',
            'cellphone' => 'required|unique:owners,cellphone,' . $id,
            'phone' => 'nullable|unique:owners,phone,' . $id,
            'address' => 'nullable|max:255',
            'holder_name' => 'nullable',
            'bank_name' => 'nullable',
            'account_type' => 'nullable|in:Ahorros,Corriente',
            'account_number' => 'nullable|integer',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Masculino,Femenino,Otro',
            'civil_status' => 'required|in:Soltero,Casado,Unión libre,Viudo,Divorciado',
            'dependent_people' => 'required|integer|max:10',
            'profession' => 'required',
            'dni_file' => 'required',
            'photo' => 'nullable',
            'comment' => 'nullable|max:255',
        ]);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }

        $bank_account = array(
            'holder_name' => $request->holder_name,
            'bank_name' => $request->bank_name,
            'account_type' => $request->account_type,
            'account_number' => $request->account_number,
        );
        // Update Owner
        $owner->update([
            'name' => $request->name,
            'lastname' => $request->lastname,
            'document_type' => $request->document_type,
            'dni' => $request->dni,
            'expedition_place' => $request->expedition_place,
            'expedition_date' => $request->expedition_date,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'address' => $request->address,
            'bank_account' => json_encode($bank_account),
            'birthdate' => $request->birthdate,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'dependent_people' => $request->dependent_people,
            'profession' => $request->profession,
            'dni_file' => $request->dni_file,
            'photo' => $request->photo,
            'comment' => $request->comment,
        ]);
        // Update User
        $user = User::where('email', $owner->email)->first();
        if ($user) {
            $user->update([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'cellphone' => $request->cellphone,
                'phone' => $request->phone,
                'address' => $request->address,
                'birthday' => $request->birthdate,
                'photo' => $request->photo,
            ]);
        }
        return $this->successResponseWithMessage('Propietario actualizado');
    }

    // Store owner with role is owner
    public function storeOwnerRole($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return $this->errorResponse('Usuario no encontrado', Response::HTTP_NOT_FOUND);
        }
        $exist_user = $this->model->where('email', $user->email)->first();
        if ($exist_user && $user->roles->count() == 0) {
            $exist_user->delete();
            return $this->successResponseWithMessage('Propietario eliminado');
        }
        if ($exist_user) {
            $exist_user->update([
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'cellphone' => $user->cellphone,
                'phone' => $user->phone,
                'address' => $user->address,
                'birthdate' => $user->birthday,
                'type' => 'holder',
                'photo' => $user->photo,
            ]);
            return $this->successResponseWithMessage('Propietario actualizado');
        } else {
            Owner::create([
                'name' => $user->name,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'cellphone' => $user->cellphone,
                'phone' => $user->phone,
                'address' => $user->address,
                'birthdate' => $user->birthday,
                'type' => 'holder',
                'photo' => $user->photo,
            ]);
            return $this->successResponseWithMessage('Propietario creado');
        }
    }
}
