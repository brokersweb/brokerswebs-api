<?php

namespace Database\Seeders;

use Database\Factories\ImmovableDetailFactory;
use Illuminate\Database\Seeder;

class ImmovableDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ImmovableDetailFactory::new()->count(9)->create();
    }
}
