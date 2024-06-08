<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\ReadjustmentRepository;
use Illuminate\Http\Request;

class ReadjustmentController extends Controller
{

    private ReadjustmentRepository $readjustmentRepository;

    public function __construct()
    {
        $this->readjustmentRepository = new ReadjustmentRepository();
    }

    public function index()
    {
        return $this->readjustmentRepository->all();
    }

    public function store(Request $request)
    {
        return $this->readjustmentRepository->create($request);
    }

    public function show($id)
    {
        return $this->readjustmentRepository->getReadjustment($id);
    }

    public function update(Request $request, $id)
    {
        return $this->readjustmentRepository->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->readjustmentRepository->delete($id);
    }
}
