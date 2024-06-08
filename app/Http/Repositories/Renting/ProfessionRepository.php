<?php

namespace App\Http\Repositories\Renting;

use App\Models\Renting\Profession;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ProfessionRepository extends Repository
{
    private $model;
    use ApiResponse;

    public function __construct()
    {
        $this->model = new Profession();
    }

    public function professions()
    {
        return $this->successResponse($this->model->orderBy('name', 'ASC')->get());
    }

    public function profession($id)
    {
        $profession = $this->model->find($id);
        if ($profession) {
            return $this->successResponse($profession);
        }
        return $this->errorResponse('Profession not found', Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request)
    {
        $rulesData = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:professions,name',
        ]);

        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }

        $profession = $this->model->create($request->all());
        return $this->successResponseWithMessage('Profesión creada exitosamente', Response::HTTP_CREATED);
    }

    public function update(Request $request, $id)
    {
        $rulesData = Validator::make($request->all(), [
            'name' => 'required|max:255,' . $id,
        ]);

        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }

        $profession = $this->model->find($id);
        if ($profession) {
            $profession->fill($request->all());

            if ($profession->isClean()) {
                return $this->errorResponse('At least one value must change', Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $profession->save();
            return $this->successResponseWithMessage('Profesión actualizada exitosamente', Response::HTTP_OK);
        }
        return $this->errorResponse('Profession not found', Response::HTTP_NOT_FOUND);
    }

    public function delete($id)
    {
        $profession = $this->model->find($id);
        if ($profession) {
            $profession->delete();
            return $this->successResponseWithMessage('Profesión eliminada exitosamente', Response::HTTP_OK);
        }
        return $this->errorResponse('Profession not found', Response::HTTP_NOT_FOUND);
    }
}
