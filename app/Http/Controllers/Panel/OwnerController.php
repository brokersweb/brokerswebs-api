<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\OwnerRepository;
use Illuminate\Http\Request;

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
}
