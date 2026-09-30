<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PayrollPeriodResource\Pages;
use App\Models\PayrollPeriod;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PayrollPeriodResource extends Resource
{
    protected static ?string $model = PayrollPeriod::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationGroup = 'HRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Period Info')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('name')
                                    ->required(),

                                Forms\Components\DatePicker::make('start_date')
                                    ->required(),

                                Forms\Components\DatePicker::make('end_date')
                                    ->required(),

                                Forms\Components\DatePicker::make('payment_date')
                                    ->required(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'processing' => 'Processing',
                                        'confirmed' => 'Confirmed',
                                        'paid' => 'Paid',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\TextInput::make('total_amount')
                                    ->numeric()
                                    ->disabled()
                                    ->prefix('Rp'),
                            ]),
                    ]),

                Forms\Components\Section::make('Payroll Items')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('employee_id')
                                    ->relationship('employee', 'first_name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('basic_salary')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('allowances')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('deductions')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('overtime_pay')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('tax')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('net_salary')
                                    ->numeric()
                                    ->disabled()
                                    ->prefix('Rp'),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'confirmed' => 'Confirmed',
                                        'paid' => 'Paid',
                                    ])
                                    ->default('draft'),

                                Forms\Components\TextInput::make('notes'),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->addActionLabel('Add Payroll Item'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('start_date')
                    ->date(),

                Tables\Columns\TextColumn::make('end_date')
                    ->date(),

                Tables\Columns\TextColumn::make('payment_date')
                    ->date(),

                Tables\Columns\TextColumn::make('total_amount')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp '),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Items'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'processing' => 'Processing',
                        'confirmed' => 'Confirmed',
                        'paid' => 'Paid',
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
            'index' => Pages\ListPayrollPeriods::route('/'),
            'create' => Pages\CreatePayrollPeriod::route('/create'),
            'edit' => Pages\EditPayrollPeriod::route('/{record}/edit'),
        ];
    }
}
