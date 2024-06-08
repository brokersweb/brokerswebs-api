<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Administrator',
        //     'lastname' => 'Administrator',
        //     'email' => 'admin@admin.com',
        //     'cellphone' => '1234567890',
        //     'password' => bcrypt('password'),
        // ]);

        $this->call([
            // CuisineTypeSeeder::class,
            // FloorTypeSeeder::class,
            // RoleSeeder::class,
            // ImmovableTypeSeeder::class,
            // ProfessionSeeder::class,
        ]);

        // $coownership = Coownership::create(['name' => 'Otra']);

        // $user = User::where('email', 'admin@admin.com')->first();
        // $user->update([
        //     'password' => bcrypt('Yonny.cm23'),
        // ]);
    }
}
