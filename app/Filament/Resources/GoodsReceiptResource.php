<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GoodsReceiptResource\Pages;
use App\Models\GoodsReceipt;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GoodsReceiptResource extends Resource
{
    protected static ?string $model = GoodsReceipt::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Purchasing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Receipt Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('purchase_order_id')
                            ->relationship('purchaseOrder', 'po_number')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('receipt_number')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('receipt_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'confirmed' => 'Confirmed',
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
                                Forms\Components\Select::make('purchase_order_item_id')
                                    ->label('PO Item'),

                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable(),

                                Forms\Components\Select::make('warehouse_location_id')
                                    ->relationship('warehouseLocation', 'name')
                                    ->nullable(),

                                Forms\Components\TextInput::make('quantity_received')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('quantity_rejected')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('unit_cost')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('notes'),
                            ])
                            ->columns(3),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('receipt_number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('purchaseOrder.po_number')
                    ->label('PO Number'),

                Tables\Columns\TextColumn::make('warehouse.name'),

                Tables\Columns\TextColumn::make('receipt_date')
                    ->date(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'confirmed' => 'success',
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
                        'confirmed' => 'Confirmed',
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
            'index' => Pages\ListGoodsReceipts::route('/'),
            'create' => Pages\CreateGoodsReceipt::route('/create'),
            'edit' => Pages\EditGoodsReceipt::route('/{record}/edit'),
        ];
    }
}
