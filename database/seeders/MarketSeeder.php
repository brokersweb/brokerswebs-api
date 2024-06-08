<?php

namespace Database\Seeders;

use App\Models\Base\Market;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MarketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markets = [
            'Servicios de tecnología de la información (TI)',
            'Telecomunicaciones',
            'Educación',
            'Alimentación y bebidas',
            'Moda y ropa',
            'Salud y cuidado personal',
            'Turismo y hostelería',
            'Entretenimiento y medios de comunicación',
            'Automotriz',
            'Manufactura',
            'Construcción',
            'Energía',
            'Servicios financieros',
            'Transporte y logística',
            'Finanzas'
        ];

        foreach ($markets as $market) {

            Market::create(['name' => $market]);
        }
    }
}
