<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\TenantRepository;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    private TenantRepository $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function index()
    {
        return $this->tenantRepository->index();
    }

    public function store(Request $request)
    {
        return $this->tenantRepository->create($request);
    }

    // Immovables
    public function getRentings($id)
    {
        return $this->tenantRepository->getImmovablesRenting($id);
    }

    public function getImmoLetter($id)
    {
        return $this->tenantRepository->getImmovableById($id);
    }
}
