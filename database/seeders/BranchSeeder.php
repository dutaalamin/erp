<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Company;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            return;
        }

        Branch::firstOrCreate(
            ['company_id' => $company->id, 'code' => 'HQ'],
            [
                'name' => 'Head Office Jakarta',
                'address' => 'Jl. Sudirman No. 1',
                'city' => 'Jakarta',
                'state' => 'DKI Jakarta',
                'country' => 'Indonesia',
                'postal_code' => '10220',
                'phone' => '+62-21-5551234',
                'email' => 'hq@erp-indonesia.com',
                'is_active' => true,
                'is_main' => true,
            ]
        );

        Branch::firstOrCreate(
            ['company_id' => $company->id, 'code' => 'SBY'],
            [
                'name' => 'Branch Surabaya',
                'address' => 'Jl. Basuki Rahmat No. 100',
                'city' => 'Surabaya',
                'state' => 'Jawa Timur',
                'country' => 'Indonesia',
                'postal_code' => '60271',
                'phone' => '+62-31-5551234',
                'email' => 'surabaya@erp-indonesia.com',
                'is_active' => true,
                'is_main' => false,
            ]
        );
    }
}
