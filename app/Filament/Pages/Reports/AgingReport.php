<?php

namespace App\Filament\Pages\Reports;

use App\Models\SalesInvoice;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AgingReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationGroup = 'Accounting';
    protected static ?string $navigationLabel = 'Aging Report';
    protected static ?int $navigationSort = 92;
    protected static string $view = 'filament.pages.reports.aging-report';

    public Collection $invoices;
    public array $summary = [];
    public string $asOfDate = '';

    public function mount(): void
    {
        $this->asOfDate = now()->toDateString();
        $this->invoices = collect();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $asOf = Carbon::parse($this->asOfDate);

        $unpaidInvoices = SalesInvoice::query()
            ->whereIn('status', ['confirmed', 'partial_paid', 'overdue'])
            ->whereColumn('paid_amount', '<', 'total_amount')
            ->with('customer')
            ->orderBy('due_date')
            ->get();

        $this->invoices = $unpaidInvoices->map(function ($invoice) use ($asOf) {
            $dueDate = Carbon::parse($invoice->due_date);
            $outstanding = $invoice->total_amount - $invoice->paid_amount;
            $daysOverdue = $dueDate->lt($asOf) ? $dueDate->diffInDays($asOf) : 0;

            $bucket = match (true) {
                $dueDate->gte($asOf) => 'current',
                $daysOverdue <= 30 => '1_30',
                $daysOverdue <= 60 => '31_60',
                $daysOverdue <= 90 => '61_90',
                default => '90_plus',
            };

            return [
                'invoice_number' => $invoice->invoice_number,
                'customer' => $invoice->customer?->name ?? '-',
                'invoice_date' => $invoice->invoice_date,
                'due_date' => $invoice->due_date,
                'total_amount' => $invoice->total_amount,
                'paid_amount' => $invoice->paid_amount,
                'outstanding' => $outstanding,
                'days_overdue' => $daysOverdue,
                'bucket' => $bucket,
            ];
        });

        $this->summary = [
            'current' => $this->invoices->where('bucket', 'current')->sum('outstanding'),
            '1_30' => $this->invoices->where('bucket', '1_30')->sum('outstanding'),
            '31_60' => $this->invoices->where('bucket', '31_60')->sum('outstanding'),
            '61_90' => $this->invoices->where('bucket', '61_90')->sum('outstanding'),
            '90_plus' => $this->invoices->where('bucket', '90_plus')->sum('outstanding'),
            'total' => $this->invoices->sum('outstanding'),
        ];
    }
}
