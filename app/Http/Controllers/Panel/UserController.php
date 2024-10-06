<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Response;

class UserController extends Controller
{

    public function index()
    {
        $users = User::select('id', 'name', 'lastname')->whereHas('roles', function ($query) {
            $query->where('name', 'Lessor');
        })->get();
        return $this->successResponse($users);
    }

    public function store()
    {
        
    }

}
