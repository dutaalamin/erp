<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseOrderResource\Pages;
use App\Models\PurchaseOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseOrderResource extends Resource
{
    protected static ?string $model = PurchaseOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Purchasing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Order Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('branch_id')
                            ->relationship('branch', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('po_number')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('order_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('expected_delivery_date')
                            ->nullable(),

                        Forms\Components\Select::make('purchase_request_id')
                            ->relationship('purchaseRequest', 'pr_number')
                            ->searchable()
                            ->nullable(),

                        Forms\Components\TextInput::make('payment_term_days')
                            ->numeric()
                            ->default(30),

                        Forms\Components\TextInput::make('currency')
                            ->default('IDR'),

                        Forms\Components\TextInput::make('exchange_rate')
                            ->numeric()
                            ->default(1),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'sent' => 'Sent',
                                'confirmed' => 'Confirmed',
                                'partial_received' => 'Partial Received',
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
                                    ->searchable()
                                    ->required(),

                                Forms\Components\TextInput::make('description'),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\Select::make('unit_of_measure_id')
                                    ->relationship('unitOfMeasure', 'name')
                                    ->searchable(),

                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('discount_percent')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('tax_percent')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('subtotal')
                                    ->numeric()
                                    ->disabled(),

                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->disabled(),
                            ])
                            ->columns(3),
                    ]),

                Forms\Components\Section::make('Totals')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->disabled()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->disabled()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('discount_amount')
                            ->numeric()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->disabled()
                            ->prefix('Rp'),
                    ])
                    ->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('po_number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('order_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'confirmed' => 'success',
                        'partial_received' => 'warning',
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
                        'sent' => 'Sent',
                        'confirmed' => 'Confirmed',
                        'partial_received' => 'Partial Received',
                        'received' => 'Received',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('supplier')
                    ->relationship('supplier', 'name'),
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route('pdf.purchase-order', $record))
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
            'index' => Pages\ListPurchaseOrders::route('/'),
            'create' => Pages\CreatePurchaseOrder::route('/create'),
            'edit' => Pages\EditPurchaseOrder::route('/{record}/edit'),
        ];
    }
}
