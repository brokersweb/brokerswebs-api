<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\User\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $roles = ['Guest', 'Administrator', 'Lessor', 'Tenant', 'Staff'];

        // foreach ($roles as $role) {
        //     Role::create(['name' => $role]);
        // }

        // $tenant = User::create([
        //     'name' => 'Inquilino',
        //     'lastname' => '1',
        //     'cellphone' => '000000',
        //     'email' => 'tenant@tenant.com',
        //     'password' => bcrypt('tenant123'),
        // ]);

        // $lessor = User::create([
        //     'name' => 'Arrendador',
        //     'lastname' => '1',
        //     'cellphone' => '111111',
        //     'email' => 'lessor@lessor.com',
        //     'password' => bcrypt('lessor123'),
        // ]);

        // $role = Role::where('name', 'Tenant')->first();
        // $tenant->roles()->attach($role->id);

        // $role1 = Role::where('name', 'Lessor')->first();
        // $lessor->roles()->attach($role1->id);

        // $user = User::create([
        //     'name' => 'Admin',
        //     'lastname' => 'Administrator',
        //     'cellphone' => '1234567890',
        //     'email' => 'admin@admin.com',
        //     'password' => bcrypt('password'),
        // ]);

        $user = User::where('email', 'Anacampillog987@gmail.com')->first();
        $role3 = Role::where('name', 'Staff')->first();
        $user->roles()->attach($role3->id);
    }
}
