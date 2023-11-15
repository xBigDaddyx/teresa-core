<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Filament\Resources\DepartmentResource\RelationManagers;
use App\Filament\Resources\DepartmentResource\RelationManagers\UsersRelationManager;
use Domain\Purchases\Models\Department;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationGroup = 'User Management';
    protected static ?string $navigationLabel = 'Department';
    protected static ?string $navigationIcon = 'tabler-briefcase';
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereBelongsTo(Filament::getTenant())->count() ?? null;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('short_name')
                            ->label(__('Short Name')),
                        Forms\Components\TextInput::make('name')
                            ->label(__('Name')),
                        Forms\Components\TextInput::make('hr_id')
                            ->label(__('HR ID')),
                        Forms\Components\TextInput::make('division_id')
                            ->label(__('Division ID')),
                    ])->columns(2),
                Forms\Components\Section::make('Finance Information')
                    ->schema([
                        Forms\Components\TextInput::make('finance_name')
                            ->label(__('Finance Name')),
                        Forms\Components\TextInput::make('finance_code')
                            ->label(__('Finance Code')),
                    ])->columns(2),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('short_name')
                    ->label(__('Short Name')),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name')),
                Tables\Columns\TextColumn::make('finance_name')
                    ->label(__('Finance Name')),
                Tables\Columns\TextColumn::make('finance_code')
                    ->label(__('Finance Code')),
                Tables\Columns\TextColumn::make('hr_id')
                    ->label(__('HR ID')),
                Tables\Columns\TextColumn::make('division_id')
                    ->label(__('Division ID')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDepartments::route('/'),
            // 'create' => Pages\CreateDepartment::route('/create'),
            // 'view' => Pages\ViewDepartment::route('/{record}'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
