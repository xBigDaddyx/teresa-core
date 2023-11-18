<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers\CompaniesRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PurchaseDepartmentRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Filament\Resources\Pages\ViewRecord;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use stdClass;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'User Management';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

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
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),
                Select::make('roles')
                // ->hidden(fn (Page $livewire): bool => $livewire instanceof EditRecord || $livewire instanceof ViewRecord)
                // ->multiple()
                // ->relationship('roles', 'name')->preload(),

                // TextInput::make('passwordConfirmation')
                //     ->password()
                //     ->dehydrated(false)
                //     ->maxLength(255)
                //     ->label(strval(__('Confirm Password'))),
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereBelongsTo(Filament::getTenant())->count() ?? null;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('id')
                //     ->sortable()
                //     ->label(strval(__('#'))),
                Tables\Columns\TextColumn::make('index')
                    ->label('#')
                    ->state(
                        static function (HasTable $livewire, stdClass $rowLoop): string {
                            return (string) ($rowLoop->iteration +
                                ($livewire->getTableRecordsPerPage() * ($livewire->getTablePage() - 1
                                ))
                            );
                        }
                    ),
                Tables\Columns\ImageColumn::make('avatar')
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->label(strval(__('Name'))),

                Tables\Columns\TextColumn::make('email')
                    ->icon('heroicon-o-envelope')
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
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

                Tables\Columns\TextColumn::make('company.name')
                    ->icon('heroicon-o-building-office-2')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('Company'))),
                Tables\Columns\TextColumn::make('department')
                    ->icon('heroicon-o-briefcase')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('Department'))),

                Tables\Columns\TextColumn::make('company.name')
                    ->icon('heroicon-o-building-office-2')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('Company'))),
                Tables\Columns\TextColumn::make('mobile')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-device-phone-mobile')
                    ->searchable()
                    ->sortable()
                    ->label(strval(__('Phone'))),
                Tables\Columns\TextColumn::make('roles.name')
                    ->color('primary')
                    ->badge()

                    ->label(strval(__('Roles'))),

                Tables\Columns\TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->dateTime('Y-m-d H:i:s')
                    ->label(strval(__('Created'))),
            ])
            ->filters([

                SelectFilter::make('department')
                    ->options(fn (): array => User::query()->where('department', '!=', null)->pluck('department', 'department')->all()),
                TernaryFilter::make('unassigned_department')
                    ->placeholder('-')
                    ->label(__('Has Department'))
                    ->trueLabel('With unassigned department records')
                    ->falseLabel('Only unassigned department records')
                    ->queries(
                        true: fn (Builder $query) => $query,
                        false: fn (Builder $query) => $query->where('department', null),
                        blank: fn (Builder $query) => $query->where('department', '!=', null),
                    ),

                TernaryFilter::make('unassigned_roles')
                    ->label(__('Has Roles'))
                    ->trueLabel('With unassigned role records')
                    ->falseLabel('Only unassigned role records')
                    ->queries(
                        true: fn (Builder $query) => $query,
                        false: fn (Builder $query) => $query->has('roles'),
                    ),
                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from'),
                        DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                Filter::make('email_verified_at')
                    ->label(__('Verified'))
                    ->query(fn (Builder $query): Builder => $query->where('email_verified_at', '!=', null))
                    ->toggle(),
            ], layout: FiltersLayout::AboveContentCollapsible)->filtersFormColumns(5)->filtersTriggerAction(
                fn (Action $action) => $action
                    ->button()
                    ->label(__('Filter')),
            )
            ->actions([
                \STS\FilamentImpersonate\Tables\Actions\Impersonate::make()
                    ->guard('ldap')
                    ->redirectTo(function (Model $record) {
                        if ($record->hasRole('purchase-user') || $record->hasRole('purchase-officer') || $record->hasRole('purchase-approver')) {
                            return route('filament.purchase.pages.dashboard', ['tenant' => Filament::getTenant()]);
                        }
                        return route('filament.admin.pages.dashboard', ['tenant' => Filament::getTenant()]);
                    }),
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
            CompaniesRelationManager::class,
            PurchaseDepartmentRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            //'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            //'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
