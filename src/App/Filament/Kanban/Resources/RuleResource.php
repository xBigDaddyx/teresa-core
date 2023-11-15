<?php

namespace App\Filament\Kanban\Resources;

use App\Filament\Kanban\Resources\RuleResource\Pages\ManageRules;
use App\Filament\Resources\RuleResource\Pages;
use App\Filament\Resources\RuleResource\RelationManagers;
use Domain\Kanban\Models\Rule;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RuleResource extends Resource
{
    protected static ?string $model = Rule::class;

    protected static ?string $navigationGroup = 'Setting';
    protected static ?string $navigationLabel = 'Rules';
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                Select::make('level')
                    ->required()
                    ->options([
                        'MAX' => 'MAX',
                        'MIDDLE' => 'MIDDLE',
                        'LOW' => 'LOW',
                    ]),
                Select::make('sewing_type')
                    ->required()
                    ->options([
                        'BRA' => 'BRA',
                        'BRIEF' => 'BRIEF'
                    ]),
                TextInput::make('value')
                    ->numeric()
                    ->required(),
            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('id'),
                TextColumn::make('name'),
                TextColumn::make('level'),
                TextColumn::make('sewing_type')
                    ->label('Sewing Type'),
                TextColumn::make('value')
                    ->label('Value'),
                TextColumn::make('unit')
                    ->label('Unit'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRules::route('/'),
        ];
    }
}
