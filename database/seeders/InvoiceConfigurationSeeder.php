<?php

namespace Database\Seeders;

use App\Models\Invoice\ConfigurationInvoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InvoiceConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ConfigurationInvoice::create([
            'authorization_number' => 12121,
            'authorization_date' => '2024-06-09',
            'date_issue' => '2024-06-09',
            'prefix' => 'FAC',
            'start_number' => 1,
            'end_number' => 1000,
            'validity' => '2024-06-09',
            'cufe' => '1234567890',
            'vat' => 19,
            'retention' => 15
        ]);
    }
}
