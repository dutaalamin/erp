<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
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

        $departments = [
            ['code' => 'FIN', 'name' => 'Finance & Accounting', 'description' => 'Handles financial operations, accounting, and reporting'],
            ['code' => 'HR', 'name' => 'Human Resources', 'description' => 'Manages recruitment, employee relations, and HR policies'],
            ['code' => 'IT', 'name' => 'Information Technology', 'description' => 'Manages IT infrastructure, software development, and technical support'],
            ['code' => 'SALES', 'name' => 'Sales & Marketing', 'description' => 'Handles sales operations, marketing campaigns, and customer relations'],
            ['code' => 'PURCH', 'name' => 'Procurement', 'description' => 'Manages purchasing, vendor relations, and supply chain'],
            ['code' => 'WH', 'name' => 'Warehouse & Logistics', 'description' => 'Handles inventory management, warehousing, and distribution'],
            ['code' => 'PROD', 'name' => 'Production', 'description' => 'Manages manufacturing operations and production planning'],
            ['code' => 'GA', 'name' => 'General Affairs', 'description' => 'Handles office management, facilities, and administrative support'],
        ];

        foreach ($departments as $dept) {
            Department::firstOrCreate(
                ['company_id' => $company->id, 'code' => $dept['code']],
                [
                    'name' => $dept['name'],
                    'description' => $dept['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
