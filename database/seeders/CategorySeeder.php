<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tools = [
            'Medición y Nivelación',
            'Corte',
            'Fijación',
            'Demolición',
            'Eléctricas y de Potencia',
            'Instalación',
            'Equipos de Seguridad',
            'Acabado',
            'Medición Avanzada',
            'Construcción Especializadas'
        ];

        foreach ($tools as $tool) {
            \App\Models\Inventory\Category::create([
                'name' => $tool,
                'type' => 'tool'
            ]);
        }
    }
}
