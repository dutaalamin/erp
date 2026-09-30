<?php

namespace App\Filament\Pages;

use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Settings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationGroup = 'Settings';
    protected static ?string $navigationLabel = 'General Settings';
    protected static ?int $navigationSort = 100;
    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $company = Company::first();

        $this->form->fill([
            'company_name' => $company?->name ?? '',
            'company_legal_name' => $company?->legal_name ?? '',
            'company_tax_id' => $company?->tax_id ?? '',
            'company_email' => $company?->email ?? '',
            'company_phone' => $company?->phone ?? '',
            'company_address' => $company?->address ?? '',
            'company_city' => $company?->city ?? '',
            'company_state' => $company?->state ?? '',
            'company_country' => $company?->country ?? 'Indonesia',
            'company_postal_code' => $company?->postal_code ?? '',
            'company_website' => $company?->website ?? '',
            'default_tax_rate' => config('erp.default_tax_rate', 11),
            'default_payment_term' => config('erp.default_payment_term', 30),
            'default_currency' => config('erp.default_currency', 'IDR'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Company Information')
                    ->schema([
                        Forms\Components\Grid::make(2)->schema([
                            Forms\Components\TextInput::make('company_name')->label('Company Name')->required(),
                            Forms\Components\TextInput::make('company_legal_name')->label('Legal Name'),
                            Forms\Components\TextInput::make('company_tax_id')->label('Tax ID (NPWP)'),
                            Forms\Components\TextInput::make('company_email')->label('Email')->email(),
                            Forms\Components\TextInput::make('company_phone')->label('Phone'),
                            Forms\Components\TextInput::make('company_website')->label('Website')->url(),
                        ]),
                        Forms\Components\Textarea::make('company_address')->label('Address')->columnSpanFull(),
                        Forms\Components\Grid::make(4)->schema([
                            Forms\Components\TextInput::make('company_city')->label('City'),
                            Forms\Components\TextInput::make('company_state')->label('State/Province'),
                            Forms\Components\TextInput::make('company_country')->label('Country'),
                            Forms\Components\TextInput::make('company_postal_code')->label('Postal Code'),
                        ]),
                    ]),
                Forms\Components\Section::make('Default Settings')
                    ->schema([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('default_tax_rate')
                                ->label('Default Tax Rate (%)')
                                ->numeric()
                                ->suffix('%'),
                            Forms\Components\TextInput::make('default_payment_term')
                                ->label('Default Payment Term (days)')
                                ->numeric()
                                ->suffix('days'),
                            Forms\Components\TextInput::make('default_currency')
                                ->label('Default Currency'),
                        ]),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $company = Company::first();
        if ($company) {
            $company->update([
                'name' => $data['company_name'],
                'legal_name' => $data['company_legal_name'],
                'tax_id' => $data['company_tax_id'],
                'email' => $data['company_email'],
                'phone' => $data['company_phone'],
                'address' => $data['company_address'],
                'city' => $data['company_city'],
                'state' => $data['company_state'],
                'country' => $data['company_country'],
                'postal_code' => $data['company_postal_code'],
                'website' => $data['company_website'],
            ]);
        }

        Notification::make()
            ->title('Settings saved successfully')
            ->success()
            ->send();
    }
}
