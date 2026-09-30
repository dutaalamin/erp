<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::firstOrCreate(
            ['tax_id' => '01.234.567.8-901.000'],
            [
                'name' => 'PT ERP Indonesia',
                'legal_name' => 'PT ERP Indonesia Tbk',
                'email' => 'info@erp-indonesia.com',
                'phone' => '+62-21-5551234',
                'address' => 'Jl. Sudirman No. 1',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'postal_code' => '10220',
                'website' => 'https://erp-indonesia.com',
                'is_active' => true,
            ]
        );
    }
}
