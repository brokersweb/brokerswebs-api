<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Repositories\SupportRepository;
use Illuminate\Http\Request;

class SupportController extends Controller{

    private SupportRepository $supportRepository;

    public function __construct(SupportRepository $supportRepository)
    {
        $this->supportRepository = $supportRepository;
    }

    public function index()
    {
        return $this->supportRepository->getSupports();
    }

    public function store(Request $request)
    {
        return $this->supportRepository->create($request);
    }

    public function show($id)
    {
        return $this->supportRepository->getSupport($id);
    }

    public function createReply(Request $request, $id)
    {
        return $this->supportRepository->createReply($request, $id);
    }
}
