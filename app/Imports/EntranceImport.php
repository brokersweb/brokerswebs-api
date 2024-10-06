<?php

namespace App\Imports;

use App\Http\Controllers\Inventory\InventoryEntranceController;
use App\Models\Inventory\InventoryEntrance;
use App\Models\Inventory\InventoryEntranceItem;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EntranceImport implements ToModel,  WithHeadingRow, WithValidation
{
    /**
     * @param array $array
     */


    public function model(array $row)
    {
        $entrance_id =  InventoryEntrance::orderBy('created_at', 'desc')->first()->id;

        return new  InventoryEntranceItem([
            'inventory_entrance_id' => $entrance_id,
            'name' => $row['nombre'],
            'code' => $row['codigo'],
            'qty' => is_numeric($row['cantidad']) ? (int)$row['cantidad'] : 1,
            'price' => $row['precio'],
            'total' => $row['precio'] * $row['cantidad'],
            'type' => is_numeric($row['tipo']) ? (int)$row['tipo'] : 1,
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nombre' => 'required|string',
            '*.codigo' => 'required',
            '*.cantidad' => 'required|numeric',
            '*.tipo' => 'required|numeric',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'codigo.required' => 'El campo código es obligatorio.',
            'cantidad.required' => 'El campo cantidad es obligatorio.',
            'cantidad.numeric' => 'El campo cantidad debe ser un número.',
            'tipo.required' => 'El campo tipo es obligatorio.',
            'tipo.numeric' => 'El campo tipo debe ser un número.',
        ];
    }
}
