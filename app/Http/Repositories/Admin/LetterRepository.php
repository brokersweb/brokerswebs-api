<?php

namespace App\Http\Repositories\Admin;

use App\Http\Resources\Admin\Tenant\LetterResource;
use App\Models\Base\LetterAdmissionExit;
use App\Models\Immovable;
use App\Models\ImmovableTenant;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LetterRepository extends Repository
{

    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new LetterAdmissionExit();
    }

    public function indexAdmissions()
    {
        $letters = LetterResource::collection($this->model::where('type', 'admission')->get());
        return $this->successResponse($letters, Response::HTTP_OK);
    }

    public function indexExits()
    {
        $letters = LetterResource::collection($this->model::where('type', 'exit')->get());
        return $this->successResponse($letters, Response::HTTP_OK);
    }

    public function indexPeaces()
    {
        $peaces = LetterResource::collection($this->model::where('type', 'peace')->get());
        return $this->successResponse($peaces, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $rulesData = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tenant_id' => 'required|exists:tenants,id',
            'immovable_id' => 'required|exists:immovables,id',
            'type' => 'required|in:admission,exit,peace',
            'file_path' => 'required',
        ]);
        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }

        $types = ['admission', 'exit', 'peace'];

        if (!in_array($request->type, $types)) {
            return $this->errorResponse('Tipo inválido', Response::HTTP_BAD_REQUEST);
        }

        $existingLetter = $this->model->where('immovable_id', $request->immovable_id)
            ->where('type', $request->type)
            ->first();

        if ($existingLetter) {
            switch ($request->type) {
                case 'admission':
                    $message = 'Ya existe una carta de ingreso para este inmueble';
                    break;
                case 'exit':
                    $message = 'Ya existe una carta de salida para este inmueble';
                    break;
                case 'peace':
                    $message = 'Ya existe un paz y salvo para este inmueble';
                    break;
            }
            return $this->errorResponse($message, Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->model->create([
                'user_id' => $request->user_id,
                'tenant_id' => $request->tenant_id,
                'immovable_id' => $request->immovable_id,
                'type' => $request->type,
                'file_path' => $request->file_path,
            ]);
            return $this->successResponseWithMessage('Carta creada exitosamente', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Ha ocurrido un error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($id)
    {
        try {
            $letter = $this->model::find($id);
            if ($letter) {
                $letter->delete();
                return $this->successResponseWithMessage('Carta eliminada exitosamente', Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró la carta', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ha ocurrido un error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getLetter($id)
    {
        try {
            $letter = $this->model::find($id);
            if ($letter) {
                return $this->successResponse($letter, Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró la carta', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ha ocurrido un error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function statusChange($id)
    {
        try {
            $letter = $this->model::find($id);
            if ($letter) {
                $letter->status = 'unavailable' ? 'available' : 'unavailable';
                $letter->save();
                return $this->successResponseWithMessage('Estado actualizado exitosamente', Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró la carta', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ha ocurrido un error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
