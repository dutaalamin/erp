<?php

namespace App\Filament\Imports;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ProductImporter extends Importer
{
    protected static ?string $model = Product::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('description')
                ->rules(['nullable', 'string']),
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['required', 'in:goods,service,raw_material,consumable']),
            ImportColumn::make('category')
                ->label('Category Code')
                ->relationship(resolveUsing: function (string $state): ?ProductCategory {
                    return ProductCategory::where('code', $state)->first();
                }),
            ImportColumn::make('unitOfMeasure')
                ->label('UoM Code')
                ->relationship(resolveUsing: function (string $state): ?UnitOfMeasure {
                    return UnitOfMeasure::where('code', $state)->first();
                }),
            ImportColumn::make('sale_price')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('purchase_price')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('cost_price')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('minimum_stock')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('barcode')
                ->rules(['nullable', 'string']),
        ];
    }

    public function resolveRecord(): ?Product
    {
        return Product::firstOrNew(['code' => $this->data['code']]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Product import completed. ' . number_format($import->successful_rows) . ' rows imported successfully.';

        if ($failedRows = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRows) . ' rows failed.';
        }

        return $body;
    }
}
