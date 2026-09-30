<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrmLeadResource\Pages;
use App\Models\CrmLead;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrmLeadResource extends Resource
{
    protected static ?string $model = CrmLead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'CRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Lead Info')
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

                                Forms\Components\TextInput::make('email')
                                    ->email(),

                                Forms\Components\TextInput::make('phone')
                                    ->tel(),

                                Forms\Components\TextInput::make('company_name'),

                                Forms\Components\Select::make('source')
                                    ->options([
                                        'website' => 'Website',
                                        'referral' => 'Referral',
                                        'social_media' => 'Social Media',
                                        'advertisement' => 'Advertisement',
                                        'cold_call' => 'Cold Call',
                                        'other' => 'Other',
                                    ])
                                    ->default('other'),

                                Forms\Components\Select::make('status')
                                    ->options([
                                        'new' => 'New',
                                        'contacted' => 'Contacted',
                                        'qualified' => 'Qualified',
                                        'proposal' => 'Proposal',
                                        'negotiation' => 'Negotiation',
                                        'won' => 'Won',
                                        'lost' => 'Lost',
                                    ])
                                    ->default('new')
                                    ->required(),

                                Forms\Components\Select::make('assigned_to')
                                    ->relationship('assignedTo', 'name')
                                    ->nullable()
                                    ->searchable()
                                    ->preload(),

                                Forms\Components\TextInput::make('expected_revenue')
                                    ->numeric()
                                    ->prefix('Rp'),

                                Forms\Components\TextInput::make('probability')
                                    ->numeric()
                                    ->suffix('%')
                                    ->minValue(0)
                                    ->maxValue(100),

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
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('email'),

                Tables\Columns\TextColumn::make('phone'),

                Tables\Columns\TextColumn::make('company_name'),

                Tables\Columns\TextColumn::make('source')
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'contacted' => 'warning',
                        'qualified' => 'success',
                        'proposal' => 'info',
                        'negotiation' => 'warning',
                        'won' => 'success',
                        'lost' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('expected_revenue')
                    ->numeric(thousandsSeparator: '.')
                    ->prefix('Rp '),

                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Assigned To'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => 'New',
                        'contacted' => 'Contacted',
                        'qualified' => 'Qualified',
                        'proposal' => 'Proposal',
                        'negotiation' => 'Negotiation',
                        'won' => 'Won',
                        'lost' => 'Lost',
                    ]),

                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'website' => 'Website',
                        'referral' => 'Referral',
                        'social_media' => 'Social Media',
                        'advertisement' => 'Advertisement',
                        'cold_call' => 'Cold Call',
                        'other' => 'Other',
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
            'index' => Pages\ListCrmLeads::route('/'),
            'create' => Pages\CreateCrmLead::route('/create'),
            'edit' => Pages\EditCrmLead::route('/{record}/edit'),
        ];
    }
}
