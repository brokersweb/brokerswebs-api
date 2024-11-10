<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\OwnerRepository;
use App\Imports\OwnerCreateImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Response;

class OwnerController extends Controller
{

    private OwnerRepository $ownerRepository;

    public function __construct()
    {
        $this->ownerRepository = new OwnerRepository();
    }

    public function index()
    {
        return $this->ownerRepository->all();
    }

    public function store(Request $request)
    {
        return $this->ownerRepository->create($request);
    }

    public function show($id)
    {
        return $this->ownerRepository->getOwner($id);
    }

    public function getOwnerByUser($id)
    {
        return $this->ownerRepository->getOwnerByUser($id);
    }

    public function updateProfile(Request $request, $id)
    {
        return $this->ownerRepository->update($request, $id);
    }


    public function importCreate(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        try {
            Excel::import(new OwnerCreateImport, $request->file('file'));
            return $this->successResponseWithMessage('Propietarios importados exitosamente', Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
