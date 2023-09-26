<?php

namespace App\Filament\Pages\Tenancy;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Illuminate\Support\HtmlString;

class EditCompanyProfile extends EditTenantProfile
{
    public static function getLabel(): string
    {
        return 'Company profile';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(3)
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
                                    ->openable()
                                    ->disabled(fn (): bool => ! auth()->user()->hasRole('super-admin')),
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
                            ])->columnSpan(2)
                            ->disabled(fn (): bool => ! auth()->user()->hasRole('super-admin')),

                    ]),
            ]);
    }
}
