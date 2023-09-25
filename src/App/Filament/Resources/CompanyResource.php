<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use Domain\Users\Models\Company;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
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

                    ]),

            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make(3)
                    ->schema([
                        Infolists\Components\Section::make()
                            ->id('gallery-section')
                            ->compact()
                            ->description(fn () => new HtmlString('<span style="word-break: break-word;">Gallery</span>'))
                            ->schema([
                                Infolists\Components\ImageEntry::make('logo')
                                    ->size(128)
                                    ->circular(),
                            ])->columnSpan(1),
                        Infolists\Components\Section::make()
                            ->id('main-section')
                            ->description(fn () => new HtmlString('<span style="word-break: break-word;">Information</span>'))
                            ->compact()
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->weight(FontWeight::Bold),
                                Infolists\Components\TextEntry::make('short_name'),
                                Infolists\Components\TextEntry::make('owner.name')
                                    ->weight(FontWeight::Bold)
                                    ->badge()
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('owner.email')
                                    ->icon('heroicon-m-envelope')
                                    ->color('info')
                                    ->copyable()
                                    ->copyMessage('Email address copied')
                                    ->copyMessageDuration(1500),

                            ])->columns([
                                'sm' => 1,
                                'lg' => 2,
                            ])->columnSpan(2),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo')
                    ->circular(),
                Tables\Columns\TextColumn::make('short_name')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['primary'])
                    ->label(strval(__('Short Name'))),
                Tables\Columns\TextColumn::make('name')
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-o-building-office-2')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    //->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['primary'])
                    ->label(strval(__('Name'))),
                Tables\Columns\TextColumn::make('owner.name')
                    ->icon('heroicon-o-user')
                    ->description(fn (Model $record): string => $record->owner->email)
                    ->badge()
                    ->sortable()
                    ->searchable()
                //->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['primary'])
                    ->label(strval(__('Owner'))),
                Tables\Columns\TextColumn::make('users_count')
                    ->badge()
                    ->label(__('Users'))
                    ->counts('users')
                    ->colors(['success']),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?? null;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageCompanies::route('/'),
            //'view' => Pages\ViewCompany::route('/{record}'),
        ];
    }
}
