<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\AccountsCollection\AccountCollection;
use App\Models\AccountsStatus\AccountStatus;
use App\Models\Base\Support;
use App\Models\Contracts\AdminContract;
use App\Models\Contracts\AdminContractRequest;
use App\Models\Immovable;
use App\Models\Owner;
use App\Models\Renting\Application;
use App\Models\Tenant;
use App\Models\User;

class CounterController extends Controller
{


    public function __construct()
    {
    }

    public function index()
    {
        $immovables = Immovable::all()->count();
        $users = User::all()->count();
        $owners = Owner::all()->count();
        $tenants = Tenant::all()->count();
        $staccounts = AccountStatus::all()->count();
        $coaccounts = AccountCollection::all()->count();
        $support = Support::where('status', '!=', 'answered')->get()->count();
        $admincontract = AdminContractRequest::where('status', '!=', 'accepted')->get()->count();
        $aplipending = Application::where('status', 'Pending')->get()->count();
        $aplinprogres = Application::where('status', 'In progress')->get()->count();

        return response()->json([
            'immovables' => $immovables,
            'users' => $users,
            'owners' => $owners,
            'tenants' => $tenants,
            'staccounts' => $staccounts,
            'coaccounts' => $coaccounts,
            'support' => $support,
            'admincontract' => $admincontract,
            'aplipending' => $aplipending,
            'aplinprogres' => $aplinprogres,
        ]);
    }
}
