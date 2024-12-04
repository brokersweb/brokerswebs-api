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
            'Ferretería',
            'Electricidad',
            'Carpintería',
            'Construcción',
            'Playa',
            'Plomería'
        ];

        foreach ($tools as $tool) {
            \App\Models\Inventory\Category::create([
                'name' => $tool,
                'type' => 'inventary',
                'subtype' => 'material'
            ]);
        }
    }
}
