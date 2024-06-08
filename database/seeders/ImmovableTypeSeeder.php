<?php

namespace Database\Seeders;

use App\Models\ImmovableType;
use Database\Factories\ImmovableTypeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImmovableTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = ['Casas unifamiliares', 'Apartamento', 'Lote', 'Locales', 'Finca', 'Casas campestres', 'Bodegas', 'Oficinas', 'Lotes agroindustriales', 'Aparta estudio'];

        foreach ($types as $type) {
            ImmovableType::create(['description' => $type]);
        }
    }
}
