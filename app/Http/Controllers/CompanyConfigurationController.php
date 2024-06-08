<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Repositories\CompanyConfigurationRepository;
use App\Http\Repositories\companyRepository;
use Illuminate\Http\Request;

class CompanyConfigurationController extends Controller{

    private CompanyConfigurationRepository $companyRepository;

    public function __construct(CompanyConfigurationRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    public function index()
    {
        return $this->companyRepository->getConfiguration();
    }

    public function store(Request $request)
    {
        return $this->companyRepository->create($request);
    }

    public function update(Request $request, $id)
    {
        return $this->companyRepository->update($request, $id);
    }
}
