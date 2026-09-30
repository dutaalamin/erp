<?php

namespace App\Filament\Widgets;

use App\Models\Employee;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $totalSales = SalesInvoice::query()
            ->whereIn('status', ['confirmed', 'partial_paid', 'paid'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        $totalPurchases = PurchaseInvoice::query()
            ->whereIn('status', ['confirmed', 'partial_paid', 'paid'])
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('total_amount');

        $pendingOrders = SalesOrder::query()
            ->whereIn('status', ['draft', 'confirmed', 'in_progress'])
            ->count();

        $activeEmployees = Employee::query()
            ->where('status', 'active')
            ->count();

        return [
            Stat::make('Total Sales', 'Rp ' . number_format($totalSales, 0, ',', '.'))
                ->description('This month')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('success')
                ->chart(array_map(fn () => rand(10, 100), range(1, 7))),

            Stat::make('Total Purchases', 'Rp ' . number_format($totalPurchases, 0, ',', '.'))
                ->description('This month')
                ->descriptionIcon('heroicon-o-banknotes')
                ->color('danger')
                ->chart(array_map(fn () => rand(10, 100), range(1, 7))),

            Stat::make('Pending Orders', $pendingOrders)
                ->description('Awaiting processing')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning')
                ->chart(array_map(fn () => rand(10, 100), range(1, 7))),

            Stat::make('Active Employees', $activeEmployees)
                ->description('Currently active')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info')
                ->chart(array_map(fn () => rand(10, 100), range(1, 7))),
        ];
    }
}
