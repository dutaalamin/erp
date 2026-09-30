<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Accounting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Info')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('payment_number')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\DatePicker::make('payment_date')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\Select::make('type')
                                    ->options([
                                        'incoming' => 'Incoming',
                                        'outgoing' => 'Outgoing',
                                    ])
                                    ->required(),

                                Forms\Components\Select::make('partner_type')
                                    ->options([
                                        'customer' => 'Customer',
                                        'supplier' => 'Supplier',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('partner_id')
                                    ->numeric(),

                                Forms\Components\Select::make('payment_method')
                                    ->options([
                                        'cash' => 'Cash',
                                        'bank_transfer' => 'Bank Transfer',
                                        'check' => 'Check',
                                        'credit_card' => 'Credit Card',
                                        'other' => 'Other',
                                    ])
                                    ->required(),

                                Forms\Components\TextInput::make('bank_account')
                                    ->nullable(),

                                Forms\Components\TextInput::make('reference')
                                    ->nullable(),

                                Forms\Components\TextInput::make('amount')
                                    ->numeric()
                                    ->prefix('Rp')
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
                                        'reconciled' => 'Reconciled',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('payment_number')
                    ->searchable(),

                Tables\Columns\TextColumn::make('payment_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'incoming' => 'success',
                        'outgoing' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('payment_method')
                    ->badge(),

                Tables\Columns\TextColumn::make('amount')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'incoming' => 'Incoming',
                        'outgoing' => 'Outgoing',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'reconciled' => 'Reconciled',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        'cash' => 'Cash',
                        'bank_transfer' => 'Bank Transfer',
                        'check' => 'Check',
                        'credit_card' => 'Credit Card',
                        'other' => 'Other',
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
