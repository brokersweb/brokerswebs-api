<?php

namespace Database\Seeders;

use Database\Factories\ImmovableFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImmovableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ImmovableFactory::new()->count(100)->create();
    }
}
