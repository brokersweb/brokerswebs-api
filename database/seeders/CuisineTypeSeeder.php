<?php

namespace Database\Seeders;

use App\Models\Base\CuisineType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CuisineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Integral',
            'Mixta',
            'SemiIntegral',
            'Sencilla',
            'Otra',
        ];

        foreach ($types as $type) {
            CuisineType::create(['name' => $type]);
        }
    }
}
