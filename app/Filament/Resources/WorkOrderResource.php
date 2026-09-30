<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkOrderResource\Pages;
use App\Models\WorkOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    protected static ?string $navigationGroup = 'Manufacturing';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Work Order Info')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('bill_of_material_id')
                                    ->relationship('billOfMaterial', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('wo_number')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->label('WO Number'),

                                Forms\Components\Select::make('product_id')
                                    ->relationship('product', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('planned_quantity')
                                    ->numeric()
                                    ->required(),

                                Forms\Components\TextInput::make('actual_quantity')
                                    ->numeric()
                                    ->default(0),

                                Forms\Components\Select::make('unit_of_measure_id')
                                    ->relationship('unitOfMeasure', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\Select::make('warehouse_id')
                                    ->relationship('warehouse', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\DatePicker::make('planned_start_date')
                                    ->required(),

                                Forms\Components\DatePicker::make('planned_end_date')
                                    ->required(),

                                Forms\Components\DatePicker::make('actual_start_date')
                                    ->nullable(),

                                Forms\Components\DatePicker::make('actual_end_date')
                                    ->nullable(),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'confirmed' => 'Confirmed',
                                        'in_progress' => 'In Progress',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\Select::make('priority')
                                    ->options([
                                        'low' => 'Low',
                                        'medium' => 'Medium',
                                        'high' => 'High',
                                        'urgent' => 'Urgent',
                                    ])
                                    ->default('medium')
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
                Tables\Columns\TextColumn::make('wo_number')
                    ->label('WO Number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product'),

                Tables\Columns\TextColumn::make('planned_quantity'),

                Tables\Columns\TextColumn::make('actual_quantity'),

                Tables\Columns\TextColumn::make('planned_start_date')
                    ->date(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'confirmed' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'confirmed' => 'Confirmed',
                        'in_progress' => 'In Progress',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
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
            'index' => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit' => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
