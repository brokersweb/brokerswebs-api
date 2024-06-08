<?php

namespace App\Interfaces;

interface ReferenceInterface
{
    public function create($request);

    public function update($request, $id);

    public function delete($id);

    public function references();

    public function reference($id);
}
