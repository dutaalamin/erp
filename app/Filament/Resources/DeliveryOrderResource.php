<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeliveryOrderResource\Pages;
use App\Models\DeliveryOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DeliveryOrderResource extends Resource
{
    protected static ?string $model = DeliveryOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Delivery Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('sales_order_id')
                            ->relationship('salesOrder', 'so_number')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('do_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('delivery_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\Textarea::make('shipping_address'),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'confirmed' => 'Confirmed',
                                'in_transit' => 'In Transit',
                                'delivered' => 'Delivered',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('draft')
                            ->required(),

                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('sales_order_item_id')
                                    ->label('SO Item')
                                    ->options(function () {
                                        return \App\Models\SalesOrderItem::query()
                                            ->with('product')
                                            ->get()
                                            ->mapWithKeys(fn ($item) => [
                                                $item->id => ($item->product?->name ?? 'N/A') . ' (Qty: ' . $item->quantity . ')',
                                            ]);
                                    })
                                    ->searchable(),

                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\Select::make('warehouse_location_id')
                                    ->relationship('warehouseLocation', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required()
                                    ->default(1),
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
                Tables\Columns\TextColumn::make('do_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('salesOrder.so_number')
                    ->label('SO Number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('warehouse.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('delivery_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'confirmed' => 'info',
                        'in_transit' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'gray',
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
                        'in_transit' => 'In Transit',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route('pdf.delivery-order', $record))
                    ->openUrlInNewTab(),
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
            'index' => Pages\ListDeliveryOrders::route('/'),
            'create' => Pages\CreateDeliveryOrder::route('/create'),
            'edit' => Pages\EditDeliveryOrder::route('/{record}/edit'),
        ];
    }
}
