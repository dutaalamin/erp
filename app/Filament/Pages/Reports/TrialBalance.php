<?php

namespace App\Filament\Pages\Reports;

use App\Models\ChartOfAccount;
use App\Models\JournalEntryLine;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class TrialBalance extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationGroup = 'Accounting';
    protected static ?string $navigationLabel = 'Trial Balance';
    protected static ?int $navigationSort = 90;
    protected static string $view = 'filament.pages.reports.trial-balance';

    public string $startDate = '';
    public string $endDate = '';
    public Collection $accounts;

    public function mount(): void
    {
        $this->startDate = now()->startOfYear()->toDateString();
        $this->endDate = now()->toDateString();
        $this->accounts = collect();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $accounts = ChartOfAccount::where('is_active', true)
            ->orderBy('code')
            ->get();

        $this->accounts = $accounts->map(function ($account) {
            $query = JournalEntryLine::where('account_id', $account->id)
                ->whereHas('journalEntry', function ($q) {
                    $q->where('status', 'posted')
                      ->whereBetween('entry_date', [$this->startDate, $this->endDate]);
                });

            $totalDebit = (clone $query)->sum('debit');
            $totalCredit = (clone $query)->sum('credit');

            $balance = $account->normal_balance === 'debit'
                ? $totalDebit - $totalCredit
                : $totalCredit - $totalDebit;

            return [
                'code' => $account->code,
                'name' => $account->name,
                'type' => $account->type,
                'debit' => $totalDebit,
                'credit' => $totalCredit,
                'balance' => $balance,
            ];
        })->filter(fn ($a) => $a['debit'] > 0 || $a['credit'] > 0);
    }
}
