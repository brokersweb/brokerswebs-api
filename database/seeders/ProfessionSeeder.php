<?php

namespace Database\Seeders;

use App\Models\Renting\Profession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $professions = [
            'Desarrollador back-end',
            'Desarrollador front-end',
            'Diseñador web',
            'Constructor',
            'Ingeniero Informático'
        ];

        foreach ($professions as $profession) {
            Profession::create(['name' => $profession]);
        }
    }
}
