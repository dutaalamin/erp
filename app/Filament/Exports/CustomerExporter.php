<?php

namespace App\Filament\Exports;

use App\Models\Customer;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CustomerExporter extends Exporter
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code'),
            ExportColumn::make('name'),
            ExportColumn::make('legal_name'),
            ExportColumn::make('type'),
            ExportColumn::make('tax_id'),
            ExportColumn::make('email'),
            ExportColumn::make('phone'),
            ExportColumn::make('address'),
            ExportColumn::make('city'),
            ExportColumn::make('state'),
            ExportColumn::make('country'),
            ExportColumn::make('postal_code'),
            ExportColumn::make('credit_limit'),
            ExportColumn::make('payment_term_days'),
            ExportColumn::make('is_active'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Customer export completed. ' . number_format($export->successful_rows) . ' rows exported.';

        if ($failedRows = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRows) . ' rows failed.';
        }

        return $body;
    }
}
