<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\LeaseDocContractRepository;
use Illuminate\Http\Request;

class LeaseDocContractController extends Controller
{

    private LeaseDocContractRepository $leaseRepository;

    public function __construct()
    {
        $this->leaseRepository = new LeaseDocContractRepository();
    }

    public function index()
    {
        return $this->leaseRepository->index();
    }

    public function store(Request $request)
    {
        return $this->leaseRepository->create($request);
    }

    public function delete($id)
    {
        return $this->leaseRepository->delete($id);
    }

    // public function status($id)
    // {
    //     return $this->leaseRepository->statusChange($id);
    // }

}
