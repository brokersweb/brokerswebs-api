<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Repositories\Admin\LetterRepository;
use Illuminate\Http\Request;

class LetterController extends Controller
{

    private LetterRepository $letterRepository;

    public function __construct()
    {
        $this->letterRepository = new LetterRepository();
    }

    public function index()
    {
        return $this->letterRepository->indexAdmissions();
    }

    public function index2()
    {
        return $this->letterRepository->indexExits();
    }
    public function index3()
    {
        return $this->letterRepository->indexPeaces();
    }

    public function store(Request $request)
    {
        return $this->letterRepository->create($request);
    }

    public function show($id)
    {
        return $this->letterRepository->getLetter($id);
    }
    public function delete($id)
    {
        return $this->letterRepository->delete($id);
    }

    public function status($id)
    {
        return $this->letterRepository->statusChange($id);
    }

}
