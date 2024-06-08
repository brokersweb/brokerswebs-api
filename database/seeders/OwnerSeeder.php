<?php

namespace Database\Seeders;

use App\Models\Immovable;
use App\Models\ImmovableOwner;
use App\Models\Owner;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@admin.com')->first();

        Owner::create([
            'name' => $user->name,
            'lastname' => $user->lastname,
            'cellphone' => $user->cellphone,
            'phone' => $user->phone,
            'email' => $user->email,
            'type' => 'holder'
        ]);

        // Asignar immovable_owner
        // 1. seleccionar todos los inmuebles excepto el de code RI-AN-06 y RI-AN-07
        // 2. asignarlos al owner donde el email es yocumo1998@gmail.com

        $owner = Owner::where('email', 'yocumo1998@gmail.com')->first();
        $immovables = Immovable::where('code', '!=', 'RI-AN-06')->where('code', '!=', 'RI-AN-07')->get();

        foreach ($immovables as $immovable) {
            ImmovableOwner::create([
                'owner_id' => $owner->id,
                'immovable_id' => $immovable->id
            ]);
        }
    }
}
