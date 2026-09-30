<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WarehouseLocationResource\Pages;
use App\Models\WarehouseLocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WarehouseLocationResource extends Resource
{
    protected static ?string $model = WarehouseLocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('warehouse_id')
                    ->relationship('warehouse', 'name')
                    ->searchable()
                    ->required(),

                Forms\Components\TextInput::make('code')
                    ->required(),

                Forms\Components\TextInput::make('name')
                    ->required(),

                Forms\Components\TextInput::make('aisle'),

                Forms\Components\TextInput::make('rack'),

                Forms\Components\TextInput::make('shelf'),

                Forms\Components\TextInput::make('bin'),

                Forms\Components\Toggle::make('is_active')
                    ->default(true),
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

                Tables\Columns\TextColumn::make('warehouse.name')
                    ->label('Warehouse')
                    ->sortable(),

                Tables\Columns\TextColumn::make('aisle'),

                Tables\Columns\TextColumn::make('rack'),

                Tables\Columns\TextColumn::make('shelf'),

                Tables\Columns\TextColumn::make('bin'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListWarehouseLocations::route('/'),
            'create' => Pages\CreateWarehouseLocation::route('/create'),
            'edit' => Pages\EditWarehouseLocation::route('/{record}/edit'),
        ];
    }
}
