<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockTransferResource\Pages;
use App\Models\StockTransfer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockTransferResource extends Resource
{
    protected static ?string $model = StockTransfer::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transfer Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable(),

                        Forms\Components\TextInput::make('transfer_number')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('from_warehouse_id')
                            ->relationship('fromWarehouse', 'name')
                            ->searchable(),

                        Forms\Components\Select::make('to_warehouse_id')
                            ->relationship('toWarehouse', 'name')
                            ->searchable(),

                        Forms\Components\DatePicker::make('transfer_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'in_transit' => 'In Transit',
                                'received' => 'Received',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft'),

                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable(),

                                Forms\Components\Select::make('from_location_id')
                                    ->relationship('fromLocation', 'name')
                                    ->searchable()
                                    ->nullable(),

                                Forms\Components\Select::make('to_location_id')
                                    ->relationship('toLocation', 'name')
                                    ->searchable()
                                    ->nullable(),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('received_quantity')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('unit_cost')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('notes'),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('transfer_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('fromWarehouse.name')
                    ->label('From Warehouse'),

                Tables\Columns\TextColumn::make('toWarehouse.name')
                    ->label('To Warehouse'),

                Tables\Columns\TextColumn::make('transfer_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'in_transit' => 'warning',
                        'received' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'in_transit' => 'In Transit',
                        'received' => 'Received',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockTransfers::route('/'),
            'create' => Pages\CreateStockTransfer::route('/create'),
            'edit' => Pages\EditStockTransfer::route('/{record}/edit'),
        ];
    }
}
