<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\ImmovableContractRepository;
use Illuminate\Http\Request;

class ImmovableContractController extends Controller
{

    private ImmovableContractRepository $immovableContractRepository;

    public function __construct()
    {
        $this->immovableContractRepository = new ImmovableContractRepository();
    }
    /* Start AdminContract Request */
    public function storeRequest(Request $request)
    {
        return $this->immovableContractRepository->storeRequest($request);
    }
}
