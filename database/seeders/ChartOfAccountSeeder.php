<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Level 1 - Top-level accounts
        $assets = $this->createAccount('1000', 'Assets', 'asset', null, 'debit', 1);
        $liabilities = $this->createAccount('2000', 'Liabilities', 'liability', null, 'credit', 1);
        $equity = $this->createAccount('3000', 'Equity', 'equity', null, 'credit', 1);
        $revenue = $this->createAccount('4000', 'Revenue', 'revenue', null, 'credit', 1);
        $expenses = $this->createAccount('5000', 'Expenses', 'expense', null, 'debit', 1);

        // Level 2 - Sub-accounts under Assets
        $currentAssets = $this->createAccount('1100', 'Current Assets', 'asset', $assets->id, 'debit', 2);
        $fixedAssets = $this->createAccount('1200', 'Fixed Assets', 'asset', $assets->id, 'debit', 2);

        // Level 3 - Detail accounts under Current Assets
        $this->createAccount('1110', 'Cash & Bank', 'asset', $currentAssets->id, 'debit', 3);
        $this->createAccount('1120', 'Accounts Receivable', 'asset', $currentAssets->id, 'debit', 3);
        $this->createAccount('1130', 'Inventory', 'asset', $currentAssets->id, 'debit', 3);
        $this->createAccount('1140', 'Prepaid Expenses', 'asset', $currentAssets->id, 'debit', 3);

        // Level 3 - Detail accounts under Fixed Assets
        $this->createAccount('1210', 'Land & Building', 'asset', $fixedAssets->id, 'debit', 3);
        $this->createAccount('1220', 'Equipment', 'asset', $fixedAssets->id, 'debit', 3);
        $this->createAccount('1230', 'Vehicles', 'asset', $fixedAssets->id, 'debit', 3);
        $this->createAccount('1240', 'Accumulated Depreciation', 'asset', $fixedAssets->id, 'credit', 3);

        // Level 2 - Sub-accounts under Liabilities
        $currentLiabilities = $this->createAccount('2100', 'Current Liabilities', 'liability', $liabilities->id, 'credit', 2);
        $longTermLiabilities = $this->createAccount('2200', 'Long-term Liabilities', 'liability', $liabilities->id, 'credit', 2);

        // Level 3 - Detail accounts under Current Liabilities
        $this->createAccount('2110', 'Accounts Payable', 'liability', $currentLiabilities->id, 'credit', 3);
        $this->createAccount('2120', 'Accrued Expenses', 'liability', $currentLiabilities->id, 'credit', 3);
        $this->createAccount('2130', 'Tax Payable', 'liability', $currentLiabilities->id, 'credit', 3);
        $this->createAccount('2140', 'Short-term Loans', 'liability', $currentLiabilities->id, 'credit', 3);

        // Level 3 - Detail accounts under Long-term Liabilities
        $this->createAccount('2210', 'Bank Loans', 'liability', $longTermLiabilities->id, 'credit', 3);

        // Level 2 - Sub-accounts under Equity
        $this->createAccount('3100', 'Share Capital', 'equity', $equity->id, 'credit', 2);
        $this->createAccount('3200', 'Retained Earnings', 'equity', $equity->id, 'credit', 2);
        $this->createAccount('3300', 'Current Year Earnings', 'equity', $equity->id, 'credit', 2);

        // Level 2 - Sub-accounts under Revenue
        $this->createAccount('4100', 'Sales Revenue', 'revenue', $revenue->id, 'credit', 2);
        $this->createAccount('4200', 'Service Revenue', 'revenue', $revenue->id, 'credit', 2);
        $this->createAccount('4300', 'Other Income', 'revenue', $revenue->id, 'credit', 2);

        // Level 2 - Sub-accounts under Expenses
        $this->createAccount('5100', 'Cost of Goods Sold', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5200', 'Salary & Wages', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5300', 'Rent Expense', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5400', 'Utilities Expense', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5500', 'Depreciation Expense', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5600', 'Marketing Expense', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5700', 'Administrative Expense', 'expense', $expenses->id, 'debit', 2);
        $this->createAccount('5800', 'Other Expenses', 'expense', $expenses->id, 'debit', 2);
    }

    /**
     * Create or find a chart of account entry.
     */
    private function createAccount(
        string $code,
        string $name,
        string $type,
        ?int $parentId,
        string $normalBalance,
        int $level
    ): ChartOfAccount {
        return ChartOfAccount::firstOrCreate(
            ['code' => $code],
            [
                'name' => $name,
                'type' => $type,
                'parent_id' => $parentId,
                'normal_balance' => $normalBalance,
                'level' => $level,
                'is_active' => true,
                'is_locked' => false,
            ]
        );
    }
}
