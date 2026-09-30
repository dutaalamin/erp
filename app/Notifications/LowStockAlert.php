<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Filament\Notifications\Notification as FilamentNotification;

class LowStockAlert extends Notification
{
    use Queueable;

    public function __construct(public Product $product, public float $currentStock) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return FilamentNotification::make()
            ->title('Low Stock Alert')
            ->body("Product \"{$this->product->name}\" (#{$this->product->code}) has low stock. Current: {$this->currentStock}, Minimum: {$this->product->minimum_stock}")
            ->icon('heroicon-o-exclamation-circle')
            ->iconColor('warning')
            ->getDatabaseMessage();
    }
}
