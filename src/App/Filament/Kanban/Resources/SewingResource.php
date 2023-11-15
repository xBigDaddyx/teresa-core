<?php

namespace App\Filament\Kanban\Resources;

use App\Filament\Kanban\Resources\SewingResource\Pages;
use App\Filament\Kanban\Resources\SewingResource\RelationManagers;
use App\Filament\Kanban\Resources\SewingResource\RelationManagers\ShiftsRelationManager;
use Domain\Kanban\Models\Sewing;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class SewingResource extends Resource
{

    protected static ?string $model = Sewing::class;
    protected static ?string $navigationGroup = 'Production';

    protected static ?string $label = 'Sewings';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->description('Define general and addition information for this sewing.')
                    ->schema([
                        Forms\Components\TextInput::make('id')
                            ->helperText('Define physical id for this sewing, suffix B for Bottom and T for Top.')
                            ->maxLength(12)
                            ->placeholder(fn (): string => Auth::user()->company->short_name . '-X-X-XXX')
                            ->prefixIcon('heroicon-m-check-badge')
                            ->required()
                            ->label(__('Physical ID')),
                        Forms\Components\Select::make('type')
                            ->helperText('Select type for this sewing.')
                            ->prefixIcon('heroicon-m-tag')
                            ->options(['BRA' => 'Bra / Top', 'BRIEF' => 'Brief / Bottom'])
                            ->required()
                            ->label(__('Type')),
                        Forms\Components\TextInput::make('display_name')
                            ->helperText('Define display name for this sewing, will displaying for wise dashboard.')
                            ->placeholder('Line XX')
                            ->prefixIcon('heroicon-m-window')
                            ->required()
                            ->label(__('Display Name')),
                    ])->columns(3),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->tooltip(fn (Model $record): string => "{$record->company->short_name}")
                    ->extraAttributes(['class' => 'transition hover:text-primary-500 cursor-pointer',]),
                Tables\Columns\TextColumn::make('id')
                    ->badge()
                    ->separator(',')
                    ->colors(['secondary'])
                    ->label('Physical'),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->separator(',')
                    ->label('Type')
                    ->colors([
                        'success' => fn ($state): bool => $state === 'BRA',
                        'danger' => fn ($state): bool => $state === 'BRIEF',
                    ])
                    ->icons([
                        'heroicon-o-sparkles' => fn ($state): bool => $state === 'BRA',
                        'heroicon-o-star' => fn ($state): bool => $state === 'BRIEF',
                    ])
                    ->iconPosition('before'),
                Tables\Columns\TextColumn::make('shifts.name')
                    ->badge()
                    ->separator(',')
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('description')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('display_name')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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

            ShiftsRelationManager::class,
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSewings::route('/'),
            'edit' => Pages\EditSewing::route('/{record}/edit'),
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
