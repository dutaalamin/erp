<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Master Data';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Personal Information')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('employee_code')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(30),
                                Forms\Components\TextInput::make('first_name')
                                    ->required()
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('last_name')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(30),
                                Forms\Components\Select::make('gender')
                                    ->options([
                                        'male' => 'Male',
                                        'female' => 'Female',
                                    ]),
                                Forms\Components\DatePicker::make('date_of_birth'),
                            ]),
                    ]),

                Forms\Components\Section::make('Employment Details')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Select::make('company_id')
                                    ->relationship('company', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Forms\Components\Select::make('branch_id')
                                    ->relationship('branch', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('department_id')
                                    ->relationship('department', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\Select::make('position_id')
                                    ->relationship('position', 'name')
                                    ->searchable()
                                    ->preload(),
                                Forms\Components\DatePicker::make('hire_date')
                                    ->required(),
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                        'probation' => 'Probation',
                                        'terminated' => 'Terminated',
                                        'resigned' => 'Resigned',
                                    ])
                                    ->required()
                                    ->default('active'),
                            ]),
                    ]),

                Forms\Components\Section::make('Address')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('city')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('state')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('country')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('postal_code')
                                    ->maxLength(20),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Banking & Tax')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('bank_name')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('bank_account_number')
                                    ->maxLength(50),
                                Forms\Components\TextInput::make('bank_account_name')
                                    ->maxLength(100),
                                Forms\Components\TextInput::make('tax_id')
                                    ->label('Tax ID')
                                    ->maxLength(50),
                            ]),
                    ])
                    ->collapsible(),

                Forms\Components\Section::make('Photo')
                    ->schema([
                        Forms\Components\FileUpload::make('photo_path')
                            ->label('Employee Photo')
                            ->image()
                            ->directory('employees/photos')
                            ->maxSize(2048),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name']),
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name')
                    ->label('Position')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'probation' => 'warning',
                        'inactive' => 'gray',
                        'terminated', 'resigned' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('hire_date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'probation' => 'Probation',
                        'terminated' => 'Terminated',
                        'resigned' => 'Resigned',
                    ]),
                Tables\Filters\SelectFilter::make('company_id')
                    ->relationship('company', 'name')
                    ->label('Company')
                    ->searchable()
                    ->preload(),
                Tables\Filters\SelectFilter::make('department_id')
                    ->relationship('department', 'name')
                    ->label('Department')
                    ->searchable()
                    ->preload(),
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
