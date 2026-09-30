<?php

namespace App\Notifications;

use App\Models\PurchaseOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class PurchaseOrderApproved extends Notification
{
    use Queueable;

    public function __construct(public PurchaseOrder $purchaseOrder) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return FilamentNotification::make()
            ->title('Purchase Order Approved')
            ->body("PO #{$this->purchaseOrder->po_number} has been approved and is ready for processing.")
            ->icon('heroicon-o-check-circle')
            ->iconColor('success')
            ->getDatabaseMessage();
    }
}
