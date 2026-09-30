<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChartOfAccountResource\Pages;
use App\Models\ChartOfAccount;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ChartOfAccountResource extends Resource
{
    protected static ?string $model = ChartOfAccount::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'Accounting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('parent_id')
                            ->relationship('parent', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload(),

                        Forms\Components\TextInput::make('code')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->required(),

                        Forms\Components\Select::make('type')
                            ->options([
                                'asset' => 'Asset',
                                'liability' => 'Liability',
                                'equity' => 'Equity',
                                'revenue' => 'Revenue',
                                'expense' => 'Expense',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('sub_type')
                            ->nullable(),

                        Forms\Components\Select::make('normal_balance')
                            ->options([
                                'debit' => 'Debit',
                                'credit' => 'Credit',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('level')
                            ->numeric()
                            ->default(1),

                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->default(true),

                        Forms\Components\Toggle::make('is_locked')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge(),

                Tables\Columns\TextColumn::make('normal_balance')
                    ->badge(),

                Tables\Columns\TextColumn::make('level'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'asset' => 'Asset',
                        'liability' => 'Liability',
                        'equity' => 'Equity',
                        'revenue' => 'Revenue',
                        'expense' => 'Expense',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active'),
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
            'index' => Pages\ListChartOfAccounts::route('/'),
            'create' => Pages\CreateChartOfAccount::route('/create'),
            'edit' => Pages\EditChartOfAccount::route('/{record}/edit'),
        ];
    }
}
