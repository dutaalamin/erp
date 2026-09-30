<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalesInvoiceResource\Pages;
use App\Models\SalesInvoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SalesInvoiceResource extends Resource
{
    protected static ?string $model = SalesInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Sales';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\Select::make('sales_order_id')
                            ->relationship('salesOrder', 'so_number')
                            ->searchable()
                            ->preload()
                            ->nullable(),

                        Forms\Components\TextInput::make('invoice_number')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('invoice_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('due_date')
                            ->required(),

                        Forms\Components\TextInput::make('currency')
                            ->default('IDR')
                            ->maxLength(10),

                        Forms\Components\TextInput::make('exchange_rate')
                            ->numeric()
                            ->default(1),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'confirmed' => 'Confirmed',
                                'partial_paid' => 'Partial Paid',
                                'paid' => 'Paid',
                                'overdue' => 'Overdue',
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
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('description')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1)
                                    ->reactive()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::calculateItemTotals($set, $get)),

                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->reactive()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::calculateItemTotals($set, $get)),

                                Forms\Components\TextInput::make('discount_percent')
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::calculateItemTotals($set, $get)),

                                Forms\Components\TextInput::make('tax_percent')
                                    ->numeric()
                                    ->default(0)
                                    ->reactive()
                                    ->afterStateUpdated(fn (Forms\Set $set, Forms\Get $get) => self::calculateItemTotals($set, $get)),

                                Forms\Components\TextInput::make('subtotal')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),

                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->disabled()
                                    ->dehydrated(),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->reorderable()
                            ->collapsible(),
                    ]),

                Forms\Components\Section::make('Totals')
                    ->schema([
                        Forms\Components\TextInput::make('subtotal')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('tax_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('discount_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),

                        Forms\Components\TextInput::make('paid_amount')
                            ->numeric()
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(5),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->date(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('paid_amount')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp '),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'confirmed' => 'info',
                        'partial_paid' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
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
                        'partial_paid' => 'Partial Paid',
                        'paid' => 'Paid',
                        'overdue' => 'Overdue',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('customer')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\Action::make('download_pdf')
                    ->label('PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->url(fn ($record) => route('pdf.sales-invoice', $record))
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
            'index' => Pages\ListSalesInvoices::route('/'),
            'create' => Pages\CreateSalesInvoice::route('/create'),
            'edit' => Pages\EditSalesInvoice::route('/{record}/edit'),
        ];
    }

    protected static function calculateItemTotals(Forms\Set $set, Forms\Get $get): void
    {
        $quantity = (float) ($get('quantity') ?? 0);
        $unitPrice = (float) ($get('unit_price') ?? 0);
        $discountPercent = (float) ($get('discount_percent') ?? 0);
        $taxPercent = (float) ($get('tax_percent') ?? 0);

        $subtotal = $quantity * $unitPrice;
        $discountAmount = $subtotal * ($discountPercent / 100);
        $afterDiscount = $subtotal - $discountAmount;
        $taxAmount = $afterDiscount * ($taxPercent / 100);
        $total = $afterDiscount + $taxAmount;

        $set('subtotal', round($subtotal, 2));
        $set('total', round($total, 2));
    }
}
