<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\AccountingService;

class PaymentObserver
{
    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        // Only process when status changes to 'confirmed'
        if ($payment->isDirty('status') && $payment->status === 'confirmed') {
            AccountingService::postPayment($payment);
        }
    }
}
