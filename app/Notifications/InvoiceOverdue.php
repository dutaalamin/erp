<?php

namespace App\Notifications;

use App\Models\SalesInvoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class InvoiceOverdue extends Notification
{
    use Queueable;

    public function __construct(public SalesInvoice $invoice) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return FilamentNotification::make()
            ->title('Invoice Overdue')
            ->body("Invoice #{$this->invoice->invoice_number} for {$this->invoice->customer->name} is overdue. Amount: Rp " . number_format($this->invoice->total_amount - $this->invoice->paid_amount, 0, ',', '.'))
            ->icon('heroicon-o-exclamation-triangle')
            ->iconColor('danger')
            ->getDatabaseMessage();
    }
}
