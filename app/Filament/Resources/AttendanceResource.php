<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages;
use App\Models\Attendance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'HRM';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(2)
                    ->schema([
                        Forms\Components\Select::make('employee_id')
                            ->relationship('employee', 'first_name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->employee_code} - {$record->first_name} {$record->last_name}")
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->default(now()),

                        Forms\Components\DateTimePicker::make('clock_in')
                            ->nullable(),

                        Forms\Components\DateTimePicker::make('clock_out')
                            ->nullable(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'present' => 'Present',
                                'absent' => 'Absent',
                                'late' => 'Late',
                                'half_day' => 'Half Day',
                                'leave' => 'Leave',
                                'holiday' => 'Holiday',
                            ])
                            ->default('present')
                            ->required(),

                        Forms\Components\TextInput::make('overtime_hours')
                            ->numeric()
                            ->default(0),

                        Forms\Components\Textarea::make('notes')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee.employee_code')
                    ->label('Employee Code')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employee.first_name')
                    ->label('First Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('clock_in')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('clock_out')
                    ->dateTime(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'present' => 'success',
                        'absent' => 'danger',
                        'late' => 'warning',
                        'half_day' => 'info',
                        'leave' => 'gray',
                        'holiday' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('overtime_hours'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'present' => 'Present',
                        'absent' => 'Absent',
                        'late' => 'Late',
                        'half_day' => 'Half Day',
                        'leave' => 'Leave',
                        'holiday' => 'Holiday',
                    ]),

                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['from'], fn ($q, $date) => $q->whereDate('date', '>=', $date))
                            ->when($data['until'], fn ($q, $date) => $q->whereDate('date', '<=', $date));
                    }),
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
            'index' => Pages\ListAttendances::route('/'),
            'create' => Pages\CreateAttendance::route('/create'),
            'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
