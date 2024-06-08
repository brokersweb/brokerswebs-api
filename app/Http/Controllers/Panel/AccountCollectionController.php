<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\AccountCollectionRepository;
use Illuminate\Http\Request;

class AccountCollectionController extends Controller
{

    private AccountCollectionRepository $accountCollectionRepository;

    public function __construct()
    {
        $this->accountCollectionRepository = new AccountCollectionRepository();
    }

    public function index()
    {
        return $this->accountCollectionRepository->all();
    }

    public function show($id)
    {
        return $this->accountCollectionRepository->getAccountCollection($id);
    }
    public function store(Request $request)
    {
        return $this->accountCollectionRepository->create($request);
    }

    public function indexByTenant($tenant_id)
    {
        return $this->accountCollectionRepository->getTenantAccountsCollection($tenant_id);
    }

    public function pdfData($id)
    {
        return $this->accountCollectionRepository->getAccountCollectionPdfData($id);
    }

    public function indexAccountCollection()
    {
        return $this->accountCollectionRepository->getAccountCollectionSelect();
    }
}
