<?php

namespace App\Observers;

use App\Models\SalesInvoice;
use App\Services\AccountingService;

class SalesInvoiceObserver
{
    /**
     * Handle the SalesInvoice "updated" event.
     */
    public function updated(SalesInvoice $salesInvoice): void
    {
        // Only process when status changes to 'confirmed'
        if ($salesInvoice->isDirty('status') && $salesInvoice->status === 'confirmed') {
            AccountingService::postSalesInvoice($salesInvoice);
        }
    }
}
