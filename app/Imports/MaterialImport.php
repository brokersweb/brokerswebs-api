<?php

namespace App\Imports;

use App\Models\Inventory\Material;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MaterialImport implements ToModel,  WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        return new Material([
            'name' => $row['nombre'],
            'code' => $row['codigo'],
            'stock' => is_numeric($row['cantidad']) ? (int)$row['cantidad'] : 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nombre' => 'required|string',
            '*.codigo' => 'required',
            '*.cantidad' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'codigo.required' => 'El campo código es obligatorio.',
            'cantidad.required' => 'El campo cantidad es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad debe ser un número.',
        ];
    }
}
