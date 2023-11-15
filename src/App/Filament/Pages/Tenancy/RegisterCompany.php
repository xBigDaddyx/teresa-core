<?php

namespace App\Filament\Pages\Tenancy;

use Domain\Users\Models\Company;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\HtmlString;

class RegisterCompany extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Register company';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

                Section::make('Gallery')
                    ->id('gallery-section')
                    ->compact()
                    ->description(fn () => new HtmlString('<span style="word-break: break-word;">Upload logo here.</span>'))
                    ->schema([
                        FileUpload::make('logo')
                            ->image()
                            ->imageEditor()
                            ->removeUploadedFileButtonPosition('center')
                            ->panelAspectRatio('null')
                            ->imagePreviewHeight('64')
                            ->imageEditorMode(2)
                            ->panelLayout('circle')
                            ->downloadable()
                            ->openable(),
                    ])->columnSpan(1),
                Section::make('Company')
                    ->id('main-section')
                    ->description(fn () => new HtmlString('<span style="word-break: break-word;">Define company information correctly.</span>'))
                    ->compact()
                    ->schema([

                        TextInput::make('name')
                            ->required()
                            ->label(strval(__('Name'))),
                        TextInput::make('short_name')
                            ->required()
                            ->label(strval(__('Short Name'))),
                        Select::make('user_id')
                            ->searchable()
                            ->relationship('owner', 'name')
                            ->label('Owner'),
                    ])->columns([
                        'sm' => 1,
                        'lg' => 2,
                    ])->columnSpan(2),

            ]);
    }

    protected function handleRegistration(array $data): Company
    {
        $company = Company::create($data);

        $company->members()->attach(auth()->user());

        return $company;
    }
}
