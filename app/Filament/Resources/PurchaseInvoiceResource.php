<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseInvoiceResource\Pages;
use App\Models\PurchaseInvoice;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseInvoiceResource extends Resource
{
    protected static ?string $model = PurchaseInvoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Purchasing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Invoice Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('supplier_id')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('purchase_order_id')
                            ->relationship('purchaseOrder', 'po_number')
                            ->searchable()
                            ->nullable(),

                        Forms\Components\TextInput::make('invoice_number')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('supplier_invoice_number'),

                        Forms\Components\DatePicker::make('invoice_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\DatePicker::make('due_date')
                            ->required(),

                        Forms\Components\TextInput::make('currency')
                            ->default('IDR'),

                        Forms\Components\TextInput::make('exchange_rate')
                            ->numeric()
                            ->default(1),

                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'confirmed' => 'Confirmed',
                                'partial_paid' => 'Partial Paid',
                                'paid' => 'Paid',
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

                                Forms\Components\TextInput::make('description'),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric(),

                                Forms\Components\TextInput::make('unit_price')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('discount_percent')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('tax_percent')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('subtotal')
                                    ->numeric()
                                    ->disabled()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('total')
                                    ->numeric()
                                    ->disabled()
                                    ->prefix('Rp'),
                            ])
                            ->columns(4),
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
                            ->disabled()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('total_amount')
                            ->numeric()
                            ->disabled()
                            ->prefix('Rp'),

                        Forms\Components\TextInput::make('paid_amount')
                            ->numeric()
                            ->prefix('Rp'),
                    ])
                    ->columns(4),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('invoice_number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('supplier.name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('invoice_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('due_date')
                    ->date(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp '),

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
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'partial_paid' => 'Partial Paid',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('supplier')
                    ->relationship('supplier', 'name'),
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
            'index' => Pages\ListPurchaseInvoices::route('/'),
            'create' => Pages\CreatePurchaseInvoice::route('/create'),
            'edit' => Pages\EditPurchaseInvoice::route('/{record}/edit'),
        ];
    }
}
