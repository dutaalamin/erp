<?php

namespace App\Filament\Widgets;

use App\Models\SalesInvoice;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class SalesChartWidget extends ChartWidget
{
    protected static ?int $sort = 2;

    protected static ?string $heading = 'Monthly Sales Revenue';

    protected function getData(): array
    {
        $months = collect(range(5, 0))->map(function ($monthsAgo) {
            return Carbon::now()->subMonths($monthsAgo);
        });

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            $yearExpr = "strftime('%Y', created_at)";
            $monthExpr = "strftime('%m', created_at)";
        } else {
            $yearExpr = 'YEAR(created_at)';
            $monthExpr = 'MONTH(created_at)';
        }

        $salesData = SalesInvoice::query()
            ->whereIn('status', ['confirmed', 'partial_paid', 'paid'])
            ->where('created_at', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw("{$yearExpr} as year"),
                DB::raw("{$monthExpr} as month"),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->keyBy(fn ($item) => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT));

        $labels = [];
        $data = [];

        foreach ($months as $month) {
            $key = $month->format('Y-m');
            $labels[] = $month->format('M Y');
            $data[] = (float) ($salesData->get($key)?->total ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sales Revenue',
                    'data' => $data,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.6)',
                    'borderColor' => 'rgb(34, 197, 94)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
