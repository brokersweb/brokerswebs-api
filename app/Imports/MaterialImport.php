<?php

namespace App\Imports;

use App\Models\Inventory\Category;
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
        $categoty = Category::where('name', '=', $row['categoria'])->first();
        $material = Material::where('code', $row['codigo'])->first();

        if ($material) {
            $material->update([
                'name' => $row['nombre'],
                'code' => $row['codigo'],
                'stock' => is_numeric($row['cantidad']) ? (int)$row['cantidad'] : 1,
                'price_basic' => $row['precio'],
                'category_id' => $categoty->id
            ]);

            return $material;
        } else {
            return new Material([
                'name' => $row['nombre'],
                'code' => $row['codigo'],
                'stock' => is_numeric($row['cantidad']) ? (int)$row['cantidad'] : 1,
                'price_basic' => $row['precio'],
                'category_id' => $categoty->id
            ]);
        }
    }

    public function rules(): array
    {
        return [
            '*.nombre' => 'required|string',
            '*.codigo' => 'required',
            '*.cantidad' => 'required|numeric',
            '*.categoria' => 'required',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'codigo.required' => 'El campo código es obligatorio.',
            'cantidad.required' => 'El campo cantidad es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad debe ser un número.',
            'categoria.required' => 'El campo categoría es obligatorio.',
        ];
    }
}
