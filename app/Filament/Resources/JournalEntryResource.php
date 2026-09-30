<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JournalEntryResource\Pages;
use App\Models\JournalEntry;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class JournalEntryResource extends Resource
{
    protected static ?string $model = JournalEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Accounting';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Entry Info')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('entry_number')
                                    ->required()
                                    ->unique(ignoreRecord: true),

                                Forms\Components\DatePicker::make('entry_date')
                                    ->required()
                                    ->default(now()),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'draft' => 'Draft',
                                        'posted' => 'Posted',
                                        'reversed' => 'Reversed',
                                    ])
                                    ->default('draft')
                                    ->required(),

                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ]),

                Forms\Components\Section::make('Lines')
                    ->schema([
                        Forms\Components\Repeater::make('lines')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('account_id')
                                    ->relationship('account', 'name')
                                    ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->code} - {$record->name}")
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Forms\Components\TextInput::make('description'),

                                Forms\Components\TextInput::make('debit')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),

                                Forms\Components\TextInput::make('credit')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->default(0),
                            ])
                            ->columns(4)
                            ->defaultItems(1)
                            ->addActionLabel('Add Line'),
                    ]),

                Forms\Components\Section::make('Totals')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('total_debit')
                                    ->numeric()
                                    ->disabled()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('total_credit')
                                    ->numeric()
                                    ->disabled()
                                    ->prefix('Rp'),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entry_number')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('entry_date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->limit(50),

                Tables\Columns\TextColumn::make('total_debit')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp '),

                Tables\Columns\TextColumn::make('total_credit')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp '),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'posted' => 'success',
                        'reversed' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'posted' => 'Posted',
                        'reversed' => 'Reversed',
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
            'index' => Pages\ListJournalEntries::route('/'),
            'create' => Pages\CreateJournalEntry::route('/create'),
            'edit' => Pages\EditJournalEntry::route('/{record}/edit'),
        ];
    }
}
