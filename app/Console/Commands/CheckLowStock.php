<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Stock;
use App\Models\User;
use App\Notifications\LowStockAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckLowStock extends Command
{
    protected $signature = 'erp:check-low-stock';
    protected $description = 'Check for products with stock below minimum level';

    public function handle(): void
    {
        $products = Product::where('is_active', true)
            ->where('minimum_stock', '>', 0)
            ->whereIn('type', ['goods', 'raw_material'])
            ->get();

        $lowStockProducts = [];

        foreach ($products as $product) {
            $totalStock = Stock::where('product_id', $product->id)->sum('quantity');

            if ($totalStock < $product->minimum_stock) {
                $lowStockProducts[] = ['product' => $product, 'stock' => $totalStock];
            }
        }

        if (empty($lowStockProducts)) {
            $this->info('All products are above minimum stock levels.');
            return;
        }

        $admins = User::role(['Super Admin', 'Admin', 'Warehouse Manager'])->get();

        foreach ($lowStockProducts as $item) {
            foreach ($admins as $admin) {
                $admin->notify(new LowStockAlert($item['product'], $item['stock']));
            }
        }

        $this->info("Found " . count($lowStockProducts) . " products with low stock. Notifications sent.");
    }
}
