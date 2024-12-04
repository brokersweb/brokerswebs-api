<?php

namespace App\Imports;

use App\Models\Inventory\Material;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MaterialPriceImport implements ToModel,  WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        $material = Material::where('code', $row['codigo'])->first();

        if ($material) {
            $material->price_basic = $row['precio'];
            $material->save();
            return null;
        }

        return null;
    }

    public function rules(): array
    {
        return [
            '*.codigo' => 'required|exists:materials,code',
            '*.precio' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'codigo.required' => 'El campo código es obligatorio.',
            'codigo.exists' => 'El código del material no existe en la base de datos.',
            'precio.required' => 'El campo precio es obligatorio.',
            'precio.numeric' => 'El campo precio debe ser un número.',
        ];
    }
}
