<?php

namespace App\Filament\Pages\Reports;

use App\Models\Product;
use App\Models\Stock;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class StockReport extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?string $navigationLabel = 'Stock Report';
    protected static ?int $navigationSort = 90;
    protected static string $view = 'filament.pages.reports.stock-report';

    public Collection $stockData;
    public string $search = '';

    public function mount(): void
    {
        $this->stockData = collect();
        $this->generateReport();
    }

    public function generateReport(): void
    {
        $query = Product::query()
            ->where('is_active', true)
            ->whereIn('type', ['goods', 'raw_material', 'consumable'])
            ->orderBy('code');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('code', 'like', "%{$this->search}%");
            });
        }

        $products = $query->get();

        $this->stockData = $products->map(function ($product) {
            $stockSummary = Stock::where('product_id', $product->id)
                ->selectRaw('COALESCE(SUM(quantity), 0) as total_qty, COALESCE(SUM(reserved_quantity), 0) as total_reserved')
                ->first();

            $totalQty = (float) $stockSummary->total_qty;
            $totalReserved = (float) $stockSummary->total_reserved;
            $available = $totalQty - $totalReserved;
            $value = $totalQty * (float) $product->cost_price;

            return [
                'code' => $product->code,
                'name' => $product->name,
                'type' => $product->type,
                'unit' => $product->unitOfMeasure?->code ?? '-',
                'total_qty' => $totalQty,
                'reserved' => $totalReserved,
                'available' => $available,
                'cost_price' => (float) $product->cost_price,
                'value' => $value,
                'minimum_stock' => (float) $product->minimum_stock,
                'is_low' => $product->minimum_stock > 0 && $totalQty < $product->minimum_stock,
            ];
        });
    }
}
