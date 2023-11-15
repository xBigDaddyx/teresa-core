<?php

namespace App\Livewire;

use Filament\Forms\Form;
use Filament\Forms;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;

class MyCustomProfile extends MyProfileComponent
{
    protected string $view = "livewire.my-custom-profile";
    public array $data;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
            ])
            ->statePath('data');
    }
}
