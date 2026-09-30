<?php

namespace App\Filament\Imports;

use App\Models\Customer;
use App\Models\Company;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CustomerImporter extends Importer
{
    protected static ?string $model = Customer::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255']),
            ImportColumn::make('legal_name')
                ->rules(['nullable', 'string']),
            ImportColumn::make('type')
                ->requiredMapping()
                ->rules(['required', 'in:individual,company']),
            ImportColumn::make('tax_id')
                ->rules(['nullable', 'string']),
            ImportColumn::make('email')
                ->rules(['nullable', 'email']),
            ImportColumn::make('phone')
                ->rules(['nullable', 'string']),
            ImportColumn::make('address')
                ->rules(['nullable', 'string']),
            ImportColumn::make('city')
                ->rules(['nullable', 'string']),
            ImportColumn::make('state')
                ->rules(['nullable', 'string']),
            ImportColumn::make('country')
                ->rules(['nullable', 'string']),
            ImportColumn::make('postal_code')
                ->rules(['nullable', 'string']),
            ImportColumn::make('credit_limit')
                ->numeric()
                ->rules(['nullable', 'numeric', 'min:0']),
            ImportColumn::make('payment_term_days')
                ->numeric()
                ->rules(['nullable', 'integer', 'min:0']),
        ];
    }

    public function resolveRecord(): ?Customer
    {
        $record = Customer::firstOrNew(['code' => $this->data['code']]);

        // Set default company if not set
        if (!$record->company_id) {
            $record->company_id = Company::first()?->id;
        }

        return $record;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Customer import completed. ' . number_format($import->successful_rows) . ' rows imported successfully.';

        if ($failedRows = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRows) . ' rows failed.';
        }

        return $body;
    }
}
