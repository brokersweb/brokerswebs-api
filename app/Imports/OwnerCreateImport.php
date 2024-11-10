<?php

namespace App\Imports;

use App\Models\Owner;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OwnerCreateImport implements ToModel,  WithHeadingRow, WithValidation
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        return new Owner([
            'name' => $row['nombre'],
            'lastname' => $row['apellidos'],
            'document_type' => 'CC',
            'dni' => $row['cedula'],
            'cellphone' => $row['celular'],
            'email' => $row['correo'],
            'birthdate' => $row['fechanacimiento'],
            'gender' => $row['genero'],
            'type' => 'holder'
        ]);
    }


    public function rules(): array
    {
        return [
            '*.nombre' => 'required|string',
            '*.cedula' => 'required',
            '*.celular' => 'required',
            '*.correo' => 'required|email',
            '*.fechanacimiento' => 'required|date',
            '*.genero' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'cedula.required' => 'El campo cédula es obligatorio.',
            'celular.required' => 'El campo celular es obligatorio.',
            'correo.required' => 'El campo correo es obligatorio.',
            'correo.email' => 'El campo correo debe ser un correo electrónico válido.',
            'fechanacimiento.required' => 'El campo fecha de nacimiento es obligatorio.',
            'fechanacimiento.date' => 'El campo fecha de nacimiento debe ser una fecha válida',
            'genero.required' => 'El campo género es obligatorio.',

        ];
    }
}
