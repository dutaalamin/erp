<?php

namespace Database\Seeders;

use App\Models\LeaveType;
use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leaveTypes = [
            [
                'code' => 'AL',
                'name' => 'Annual Leave',
                'max_days_per_year' => 12,
                'is_paid' => true,
                'description' => 'Annual paid leave entitlement for all employees',
            ],
            [
                'code' => 'SL',
                'name' => 'Sick Leave',
                'max_days_per_year' => 14,
                'is_paid' => true,
                'description' => 'Paid sick leave with medical certificate',
            ],
            [
                'code' => 'ML',
                'name' => 'Maternity Leave',
                'max_days_per_year' => 90,
                'is_paid' => true,
                'description' => 'Maternity leave for female employees',
            ],
            [
                'code' => 'PL',
                'name' => 'Paternity Leave',
                'max_days_per_year' => 3,
                'is_paid' => true,
                'description' => 'Paternity leave for male employees',
            ],
            [
                'code' => 'UL',
                'name' => 'Unpaid Leave',
                'max_days_per_year' => 30,
                'is_paid' => false,
                'description' => 'Leave without pay, subject to approval',
            ],
            [
                'code' => 'CL',
                'name' => 'Compassionate Leave',
                'max_days_per_year' => 3,
                'is_paid' => true,
                'description' => 'Leave for bereavement or family emergencies',
            ],
        ];

        foreach ($leaveTypes as $type) {
            LeaveType::firstOrCreate(
                ['code' => $type['code']],
                [
                    'name' => $type['name'],
                    'max_days_per_year' => $type['max_days_per_year'],
                    'is_paid' => $type['is_paid'],
                    'description' => $type['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
