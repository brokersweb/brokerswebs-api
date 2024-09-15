<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\AccountStatusRepository;
use Illuminate\Http\Request;

class AccountStatusController extends Controller
{

    private AccountStatusRepository $accountStatusRepository;

    public function __construct()
    {
        $this->accountStatusRepository = new AccountStatusRepository();
    }

    public function index()
    {
        return $this->accountStatusRepository->all();
    }

    public function showMe($id)
    {
        return $this->accountStatusRepository->getAccountStatus($id);
    }

    public function store(Request $request)
    {
        return $this->accountStatusRepository->create($request);
    }

    public function indexByOwner($owner_id)
    {
        return $this->accountStatusRepository->getOwnerAccountStatus($owner_id);
    }


    public function indexImmovablesAc()
    {
        return $this->accountStatusRepository->getImmovables();
    }

    public function download($id)
    {
        return $this->accountStatusRepository->download($id);
    }

    public function cancel($id)
    {
        return $this->accountStatusRepository->cancelAccStatus($id);
    }

    public function invoiceSentCustomer($id)
    {
        return $this->accountStatusRepository->sendToCustomerInvoice($id);
    }
}
