<?php

namespace Database\Seeders;

use App\Models\Base\FloorType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FloorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Alfombra',
            'Baldosa Comun',
            'Cemento',
            'Ceramica',
            'Madera',
            'Madera Laminada',
            'Marmol',
            'Porcelanato',
            'Retal Marmol',
            'Tapete',
            'Otro'
        ];

        foreach ($types as $type) {
            FloorType::create(['name' => $type]);
        }
    }
}
