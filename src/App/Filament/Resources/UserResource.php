<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use Domain\Users\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(strval(__('Name')))
                    ->required(),

                TextInput::make('email')
                    ->required()
                    ->email()
                    ->unique(table: static::getModel(), ignorable: fn ($record) => $record)
                    ->label(strval(__('Email'))),

                TextInput::make('password')
                    ->type('password')
                    ->minLength(5)
                    ->required(fn (Page $livewire): bool => $livewire instanceof CreateRecord)
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state)),
                Select::make('roles')
                    ->hidden(fn (Page $livewire): bool => $livewire instanceof EditRecord)
                    ->multiple()
                    ->relationship('roles', 'name')->preload(),

                // TextInput::make('passwordConfirmation')
                //     ->password()
                //     ->dehydrated(false)
                //     ->maxLength(255)
                //     ->label(strval(__('Confirm Password'))),
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
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->label(strval(__('#'))),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('Name'))),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('Email'))),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->icon(fn (string $state): string => match ($state) {
                        default => 'heroicon-o-check-circle',
                        fn ($state): bool => $state === null => 'heroicon-o-x-circle',

                    })
                    ->color(fn (string $state): string => match ($state) {
                        default => 'success',
                        fn ($state): bool => $state === null => 'danger',

                    })
                    ->label(strval(__('Verified'))),

                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->separator(',')
                    ->label(strval(__('Roles'))),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('Created'))),
            ])
            ->filters([
                //
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
        return [
            RolesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
