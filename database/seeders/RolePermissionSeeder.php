<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all modules
        $modules = [
            'company',
            'branch',
            'department',
            'position',
            'employee',
            'customer',
            'supplier',
            'product',
            'warehouse',
            'stock_adjustment',
            'stock_transfer',
            'purchase_request',
            'purchase_order',
            'goods_receipt',
            'purchase_invoice',
            'quotation',
            'sales_order',
            'delivery_order',
            'sales_invoice',
            'chart_of_account',
            'journal_entry',
            'payment',
            'attendance',
            'leave_request',
            'payroll',
            'crm_lead',
            'crm_activity',
            'bill_of_material',
            'work_order',
        ];

        // Define actions
        $actions = ['view', 'create', 'update', 'delete'];

        // Create permissions for each module
        $allPermissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = "{$action}_{$module}";
                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                $allPermissions[] = $permissionName;
            }
        }

        // Create roles
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $manager = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'web']);
        $accountant = Role::firstOrCreate(['name' => 'Accountant', 'guard_name' => 'web']);
        $hrManager = Role::firstOrCreate(['name' => 'HR Manager', 'guard_name' => 'web']);
        $salesManager = Role::firstOrCreate(['name' => 'Sales Manager', 'guard_name' => 'web']);
        $purchaseManager = Role::firstOrCreate(['name' => 'Purchase Manager', 'guard_name' => 'web']);
        $warehouseManager = Role::firstOrCreate(['name' => 'Warehouse Manager', 'guard_name' => 'web']);
        $employee = Role::firstOrCreate(['name' => 'Employee', 'guard_name' => 'web']);

        // Assign all permissions to Super Admin
        $superAdmin->syncPermissions($allPermissions);

        // Admin gets all except delete on critical modules
        $adminPermissions = collect($allPermissions)->filter(function ($permission) {
            $criticalDeletes = [
                'delete_company',
                'delete_chart_of_account',
                'delete_journal_entry',
            ];
            return !in_array($permission, $criticalDeletes);
        })->toArray();
        $admin->syncPermissions($adminPermissions);

        // Manager gets view, create, update on all modules
        $managerPermissions = collect($allPermissions)->filter(function ($permission) {
            return !str_starts_with($permission, 'delete_');
        })->toArray();
        $manager->syncPermissions($managerPermissions);

        // Accountant - Finance related modules
        $accountantModules = [
            'chart_of_account',
            'journal_entry',
            'payment',
            'purchase_invoice',
            'sales_invoice',
        ];
        $accountantPermissions = [];
        foreach ($accountantModules as $module) {
            foreach ($actions as $action) {
                $accountantPermissions[] = "{$action}_{$module}";
            }
        }
        // Accountant also gets view on related modules
        $accountantViewModules = ['customer', 'supplier', 'employee', 'company', 'branch'];
        foreach ($accountantViewModules as $module) {
            $accountantPermissions[] = "view_{$module}";
        }
        $accountant->syncPermissions($accountantPermissions);

        // HR Manager - HR related modules
        $hrModules = ['employee', 'department', 'position', 'attendance', 'leave_request', 'payroll'];
        $hrPermissions = [];
        foreach ($hrModules as $module) {
            foreach ($actions as $action) {
                $hrPermissions[] = "{$action}_{$module}";
            }
        }
        $hrPermissions[] = 'view_company';
        $hrPermissions[] = 'view_branch';
        $hrManager->syncPermissions($hrPermissions);

        // Sales Manager - Sales related modules
        $salesModules = ['customer', 'quotation', 'sales_order', 'delivery_order', 'sales_invoice', 'crm_lead', 'crm_activity'];
        $salesPermissions = [];
        foreach ($salesModules as $module) {
            foreach ($actions as $action) {
                $salesPermissions[] = "{$action}_{$module}";
            }
        }
        $salesPermissions[] = 'view_product';
        $salesPermissions[] = 'view_warehouse';
        $salesManager->syncPermissions($salesPermissions);

        // Purchase Manager - Purchase related modules
        $purchaseModules = ['supplier', 'purchase_request', 'purchase_order', 'goods_receipt', 'purchase_invoice'];
        $purchasePermissions = [];
        foreach ($purchaseModules as $module) {
            foreach ($actions as $action) {
                $purchasePermissions[] = "{$action}_{$module}";
            }
        }
        $purchasePermissions[] = 'view_product';
        $purchasePermissions[] = 'view_warehouse';
        $purchaseManager->syncPermissions($purchasePermissions);

        // Warehouse Manager - Warehouse related modules
        $warehouseModules = ['warehouse', 'product', 'stock_adjustment', 'stock_transfer', 'bill_of_material', 'work_order'];
        $warehousePermissions = [];
        foreach ($warehouseModules as $module) {
            foreach ($actions as $action) {
                $warehousePermissions[] = "{$action}_{$module}";
            }
        }
        $warehousePermissions[] = 'view_goods_receipt';
        $warehousePermissions[] = 'view_delivery_order';
        $warehouseManager->syncPermissions($warehousePermissions);

        // Employee - Limited view access + own attendance/leave
        $employeePermissions = [
            'view_company',
            'view_branch',
            'view_department',
            'view_position',
            'view_attendance',
            'create_attendance',
            'view_leave_request',
            'create_leave_request',
            'update_leave_request',
        ];
        $employee->syncPermissions($employeePermissions);
    }
}
