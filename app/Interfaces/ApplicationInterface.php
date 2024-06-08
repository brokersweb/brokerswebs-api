<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface ApplicationInterface
{
    public function all();

    public function show($id);

    public function changeStatus(Request $request, $id);
}
