<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationLabel = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Info')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->label('Category')
                                    ->relationship('category', 'name')
                                    ->preload()
                                    ->required(),
                                Forms\Components\TextInput::make('code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Select::make('type')
                                    ->options([
                                        'goods' => 'Goods',
                                        'service' => 'Service',
                                        'raw_material' => 'Raw Material',
                                        'consumable' => 'Consumable',
                                    ])
                                    ->required(),
                                Forms\Components\Textarea::make('description')
                                    ->maxLength(1000)
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Section::make('Units')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('unit_of_measure_id')
                                    ->label('Unit of Measure')
                                    ->relationship('unitOfMeasure', 'name')
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('purchase_unit_of_measure_id')
                                    ->label('Purchase Unit of Measure')
                                    ->relationship('purchaseUnitOfMeasure', 'name')
                                    ->preload()
                                    ->nullable(),
                            ]),
                    ]),

                Forms\Components\Section::make('Pricing')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\TextInput::make('sale_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                                Forms\Components\TextInput::make('purchase_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                                Forms\Components\TextInput::make('cost_price')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                            ]),
                    ]),

                Forms\Components\Section::make('Stock & Identification')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('minimum_stock')
                                    ->numeric()
                                    ->default(0),
                                Forms\Components\TextInput::make('weight')
                                    ->numeric()
                                    ->nullable(),
                                Forms\Components\TextInput::make('barcode')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('internal_reference')
                                    ->maxLength(100),
                            ]),
                    ]),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\Grid::make(3)
                            ->schema([
                                Forms\Components\Toggle::make('is_purchasable')
                                    ->label('Purchasable')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_sellable')
                                    ->label('Sellable')
                                    ->default(true),
                                Forms\Components\Toggle::make('is_active')
                                    ->label('Active')
                                    ->default(true),
                            ]),
                    ]),

                Forms\Components\Section::make('Image')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->label('Product Image')
                            ->image()
                            ->directory('products'),
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
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'goods' => 'success',
                        'service' => 'info',
                        'raw_material' => 'warning',
                        'consumable' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('sale_price')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('purchase_price')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp ')
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimum_stock')
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
                        'goods' => 'Goods',
                        'service' => 'Service',
                        'raw_material' => 'Raw Material',
                        'consumable' => 'Consumable',
                    ]),
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
