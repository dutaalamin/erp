<?php

namespace App\Filament\Pages\Reports;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class ProfitLoss extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Accounting';
    protected static ?string $navigationLabel = 'Profit & Loss';
    protected static ?int $navigationSort = 91;
    protected static string $view = 'filament.pages.reports.profit-loss';

    public string $startDate = '';
    public string $endDate = '';
    public Collection $revenueAccounts;
    public Collection $expenseAccounts;
    public float $totalRevenue = 0;
    public float $totalExpense = 0;
    public float $netProfitLoss = 0;

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->toDateString();
        $this->endDate = now()->toDateString();
        $this->revenueAccounts = collect();
        $this->expenseAccounts = collect();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $this->revenueAccounts = $this->getAccountsByType('revenue');
        $this->expenseAccounts = $this->getAccountsByType('expense');

        $this->totalRevenue = $this->revenueAccounts->sum('balance');
        $this->totalExpense = $this->expenseAccounts->sum('balance');
        $this->netProfitLoss = $this->totalRevenue - $this->totalExpense;
    }

    private function getAccountsByType(string $type): Collection
    {
        $accounts = ChartOfAccount::where('is_active', true)
            ->where('type', $type)
            ->orderBy('code')
            ->get();

        return $accounts->map(function ($account) {
            $query = JournalEntryLine::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($q) {
                    $q->where('status', 'posted')
                      ->whereBetween('entry_date', [$this->startDate, $this->endDate]);
                });

            $totalDebit = (clone $query)->sum('debit');
            $totalCredit = (clone $query)->sum('credit');

            // Revenue: normal balance is credit (credit - debit)
            // Expense: normal balance is debit (debit - credit)
            $balance = $account->normal_balance === 'credit'
                ? $totalCredit - $totalDebit
                : $totalDebit - $totalCredit;

            return [
                'code' => $account->code,
                'name' => $account->name,
                'balance' => $balance,
            ];
        })->filter(fn ($a) => $a['balance'] != 0);
    }
}
