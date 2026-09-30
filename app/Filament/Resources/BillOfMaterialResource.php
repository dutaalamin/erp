<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BillOfMaterialResource\Pages;
use App\Models\BillOfMaterial;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BillOfMaterialResource extends Resource
{
    protected static ?string $model = BillOfMaterial::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'Manufacturing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('BOM Info')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Finished Product'),

                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\TextInput::make('name')
                                    ->required(),

                                Forms\Components\TextInput::make('version')
                                    ->default('1.0'),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->default(1),

                                Forms\Components\Select::make('unit_of_measure_id')
                                    ->relationship('unitOfMeasure', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Toggle::make('is_active')
                                    ->default(true),

                                Forms\Components\Textarea::make('notes')
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Section::make('Materials')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label('Raw Material'),

                                Forms\Components\TextInput::make('quantity')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\Select::make('unit_of_measure_id')
                                    ->relationship('unitOfMeasure', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('waste_percent')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('sort_order')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\TextInput::make('notes'),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Add Material'),
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

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product'),

                Tables\Columns\TextColumn::make('version'),

                Tables\Columns\TextColumn::make('quantity'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('items_count')
                    ->counts('items')
                    ->label('Materials'),
            ])
            ->filters([
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
            'index' => Pages\ListBillOfMaterials::route('/'),
            'create' => Pages\CreateBillOfMaterial::route('/create'),
            'edit' => Pages\EditBillOfMaterial::route('/{record}/edit'),
        ];
    }
}
