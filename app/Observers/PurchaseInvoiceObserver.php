<?php

namespace App\Observers;

use App\Models\PurchaseInvoice;
use App\Services\AccountingService;

class PurchaseInvoiceObserver
{
    /**
     * Handle the PurchaseInvoice "updated" event.
     */
    public function updated(PurchaseInvoice $purchaseInvoice): void
    {
        // Only process when status changes to 'confirmed'
        if ($purchaseInvoice->isDirty('status') && $purchaseInvoice->status === 'confirmed') {
            AccountingService::postPurchaseInvoice($purchaseInvoice);
        }
    }
}
