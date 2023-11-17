<?php

namespace App\Filament\Purchase\Resources\SupplierResource\Pages;

use App\Filament\Purchase\Resources\SupplierResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms;
use Illuminate\Database\Eloquent\Builder;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class CreateSupplier extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;
    protected static string $resource = SupplierResource::class;
    protected function getSteps(): array
    {
        return [
            Forms\Components\Wizard\Step::make('General Infomation')
                ->description('Supplier general information')
                ->icon('tabler-building-store')
                ->schema([
                    Forms\Components\Grid::make(3)
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->label(__('Name')),
                            Forms\Components\Select::make('currency_id')
                                ->relationship('currency', 'sign', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()))
                                ->label(__('Currency')),
                            Forms\Components\Select::make('city_id')
                                ->relationship('city', 'name', modifyQueryUsing: fn (Builder $query) => $query->whereBelongsTo(Filament::getTenant()))
                                ->label(__('City')),
                            Forms\Components\Textarea::make('address')
                                ->label(__('Address'))
                                ->columnSpanFull(),
                        ])

                ]),
            Forms\Components\Wizard\Step::make('Contact Person')
                ->description('Supplier contact person information')
                ->icon('tabler-id')
                ->schema([
                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\TextInput::make('contact_person')
                                ->label(__('Contact Person Name')),
                            PhoneInput::make('phone')
                                ->label(__('Phone')),
                            PhoneInput::make('mobile')
                                ->label(__('Mobile')),
                            Forms\Components\TextInput::make('fax')
                                ->label(__('Fax')),
                            Forms\Components\TextInput::make('email')
                                ->label(__('Email')),

                        ])

                ]),

        ];
    }
}
