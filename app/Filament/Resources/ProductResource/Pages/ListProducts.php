<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Filament\Imports\ProductImporter;
use App\Filament\Exports\ProductExporter;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Actions\ExportAction;
use Filament\Resources\Pages\ListRecords;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            ImportAction::make()
                ->importer(ProductImporter::class),
            ExportAction::make()
                ->exporter(ProductExporter::class),
        ];
    }
}
