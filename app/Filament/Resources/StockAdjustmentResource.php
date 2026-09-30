<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockAdjustmentResource\Pages;
use App\Models\StockAdjustment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StockAdjustmentResource extends Resource
{
    protected static ?string $model = StockAdjustment::class;

    protected static ?string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Adjustment Info')
                    ->schema([
                        Forms\Components\Select::make('company_id')
                            ->relationship('company', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\Select::make('warehouse_id')
                            ->relationship('warehouse', 'name')
                            ->searchable()
                            ->required(),

                        Forms\Components\TextInput::make('adjustment_number')
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\DatePicker::make('adjustment_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\Select::make('type')
                            ->options([
                                'addition' => 'Addition',
                                'reduction' => 'Reduction',
                                'opname' => 'Opname',
                            ])
                            ->required(),

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
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable(),

                                Forms\Components\Select::make('warehouse_location_id')
                                    ->relationship('warehouseLocation', 'name')
                                    ->searchable()
                                    ->nullable(),

                                Forms\Components\TextInput::make('system_quantity')
                                    ->numeric(),

                                Forms\Components\TextInput::make('actual_quantity')
                                    ->numeric()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {
                                        $system = (float) $get('system_quantity');
                                        $actual = (float) $get('actual_quantity');
                                        $set('difference_quantity', $actual - $system);
                                    }),

                                Forms\Components\TextInput::make('difference_quantity')
                                    ->numeric()
                                    ->disabled()
                                    ->dehydrated(),

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
                Tables\Columns\TextColumn::make('adjustment_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Warehouse'),

                Tables\Columns\TextColumn::make('adjustment_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge(),

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
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'addition' => 'Addition',
                        'reduction' => 'Reduction',
                        'opname' => 'Opname',
                    ]),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('warehouse')
                    ->relationship('warehouse', 'name'),
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
            'index' => Pages\ListStockAdjustments::route('/'),
            'create' => Pages\CreateStockAdjustment::route('/create'),
            'edit' => Pages\EditStockAdjustment::route('/{record}/edit'),
        ];
    }
}
