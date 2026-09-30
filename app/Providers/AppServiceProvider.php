<?php

namespace App\Providers;

use App\Models\DeliveryOrder;
use App\Models\GoodsReceipt;
use App\Models\Payment;
use App\Models\PurchaseInvoice;
use App\Models\SalesInvoice;
use App\Models\StockAdjustment;
use App\Observers\DeliveryOrderObserver;
use App\Observers\GoodsReceiptObserver;
use App\Observers\PaymentObserver;
use App\Observers\PurchaseInvoiceObserver;
use App\Observers\SalesInvoiceObserver;
use App\Observers\StockAdjustmentObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Stock auto-update observers
        GoodsReceipt::observe(GoodsReceiptObserver::class);
        DeliveryOrder::observe(DeliveryOrderObserver::class);
        StockAdjustment::observe(StockAdjustmentObserver::class);

        // Journal auto-posting observers
        SalesInvoice::observe(SalesInvoiceObserver::class);
        PurchaseInvoice::observe(PurchaseInvoiceObserver::class);
        Payment::observe(PaymentObserver::class);
    }
}
