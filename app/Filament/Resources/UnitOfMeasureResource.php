<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitOfMeasureResource\Pages;
use App\Models\UnitOfMeasure;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitOfMeasureResource extends Resource
{
    protected static ?string $model = UnitOfMeasure::class;

    protected static ?string $navigationIcon = 'heroicon-o-scale';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 8;

    protected static ?string $navigationLabel = 'Units of Measure';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Unit of Measure Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(20),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('symbol')
                                    ->maxLength(10),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'unit' => 'Unit',
                                        'weight' => 'Weight',
                                        'length' => 'Length',
                                        'volume' => 'Volume',
                                        'area' => 'Area',
                                        'time' => 'Time',
                                    ])
                                    ->required()
                                    ->default('unit'),
                                Forms\Components\Select::make('base_unit_id')
                                    ->label('Base Unit')
                                    ->relationship('baseUnit', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),
                                Forms\Components\TextInput::make('conversion_factor')
                                    ->numeric()
                                    ->default(1)
                                    ->minValue(0),
                            ]),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->default(true),
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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('symbol')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'unit' => 'gray',
                        'weight' => 'info',
                        'length' => 'success',
                        'volume' => 'warning',
                        'area' => 'primary',
                        'time' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('conversion_factor')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'unit' => 'Unit',
                        'weight' => 'Weight',
                        'length' => 'Length',
                        'volume' => 'Volume',
                        'area' => 'Area',
                        'time' => 'Time',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
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
            'index' => Pages\ListUnitOfMeasures::route('/'),
            'create' => Pages\CreateUnitOfMeasure::route('/create'),
            'edit' => Pages\EditUnitOfMeasure::route('/{record}/edit'),
        ];
    }
}
