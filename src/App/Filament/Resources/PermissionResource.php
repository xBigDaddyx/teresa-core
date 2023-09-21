<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Section::make('Permission')
                            ->description(fn () => new HtmlString('<span style="word-break: break-word;">Define permissions correctly.</span>'))
                            ->compact()
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->label(strval(__('Name'))),

                                TextInput::make('guard_name')
                                    ->required()
                                    ->label(strval(__('Guard Name')))
                                    ->default(config('auth.defaults.guard')),
                                Select::make('roles')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->label('Roles'),
                            ])->columns([
                                'sm' => 2,
                                'lg' => 3,
                            ]),

                    ]),

                // Forms\Components\Select::make('http_path')
                //     ->options(FilamentAuthenticate::allRoutes())
                //     ->searchable()
                //     ->label(strval(__('HTTP Path'))),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count() ?? null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->colors(['primary'])
                    ->label(strval(__('Name'))),

                Tables\Columns\TextColumn::make('guard_name')
                    ->badge()
                    ->sortable()
                    ->searchable()
                    ->formatStateUsing(fn ($state): string => Str::headline($state))
                    ->label(strval(__('Guard Name'))),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->separator(',')
                    ->label(__('Roles Name'))
                    ->toggleable(true, isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('roles_count')
                    ->badge()
                    ->label(__('Roles'))
                    ->counts('roles')
                    ->colors(['success']),

                // Tables\Columns\TextColumn::make('http_path')
                //     ->label(strval(__('HTTP PATH'))),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('Updated'))),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePermissions::route('/'),
        ];
    }
}
