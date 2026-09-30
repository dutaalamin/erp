<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrmActivityResource\Pages;
use App\Models\CrmActivity;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrmActivityResource extends Resource
{
    protected static ?string $model = CrmActivity::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('lead_id')
                            ->relationship('lead', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('customer_id')
                            ->relationship('customer', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload(),

                        Forms\Components\Select::make('type')
                            ->options([
                                'call' => 'Call',
                                'email' => 'Email',
                                'meeting' => 'Meeting',
                                'note' => 'Note',
                                'task' => 'Task',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('subject')
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('activity_date')
                            ->required()
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('completed_at')
                            ->nullable(),

                        Forms\Components\Select::make('assigned_to')
                            ->relationship('assignedTo', 'name')
                            ->nullable()
                            ->searchable()
                            ->preload(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable(),

                Tables\Columns\TextColumn::make('type')
                    ->badge(),

                Tables\Columns\TextColumn::make('lead.name')
                    ->label('Lead'),

                Tables\Columns\TextColumn::make('customer.name')
                    ->label('Customer'),

                Tables\Columns\TextColumn::make('activity_date')
                    ->dateTime()
                    ->sortable(),

                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'call' => 'Call',
                        'email' => 'Email',
                        'meeting' => 'Meeting',
                        'note' => 'Note',
                        'task' => 'Task',
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
            'index' => Pages\ListCrmActivities::route('/'),
            'create' => Pages\CreateCrmActivity::route('/create'),
            'edit' => Pages\EditCrmActivity::route('/{record}/edit'),
        ];
    }
}
