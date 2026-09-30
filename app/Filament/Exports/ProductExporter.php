<?php

namespace App\Filament\Exports;

use App\Models\Product;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProductExporter extends Exporter
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code'),
            ExportColumn::make('name'),
            ExportColumn::make('description'),
            ExportColumn::make('type'),
            ExportColumn::make('category.code')->label('Category Code'),
            ExportColumn::make('category.name')->label('Category Name'),
            ExportColumn::make('unitOfMeasure.code')->label('UoM Code'),
            ExportColumn::make('sale_price'),
            ExportColumn::make('purchase_price'),
            ExportColumn::make('cost_price'),
            ExportColumn::make('minimum_stock'),
            ExportColumn::make('barcode'),
            ExportColumn::make('is_active'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Product export completed. ' . number_format($export->successful_rows) . ' rows exported.';

        if ($failedRows = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRows) . ' rows failed.';
        }

        return $body;
    }
}
