<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ImmovableRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ImmovableRequestController extends Controller
{
    private ImmovableRequestRepository $immovableRequestRepository;

    public function __construct()
    {
        $this->immovableRequestRepository = new ImmovableRequestRepository();
    }

    public function index()
    {
        return $this->immovableRequestRepository->all();
    }

    public function store(Request $request)
    {
        return $this->immovableRequestRepository->create($request);
    }

    public function show($id)
    {
        return $this->immovableRequestRepository->getRequest($id);
    }
}
