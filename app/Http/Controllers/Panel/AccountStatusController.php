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

    public function show($id)
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

    public function pdfData($id)
    {
        return $this->accountStatusRepository->getAccountStatusPdfData($id);
    }

    public function indexAccount()
    {
        return $this->accountStatusRepository->getAccountStatusSelect();
    }

    public function downloadInvoice()
    {
        return $this->accountStatusRepository->downloadInvoice();
    }
}
