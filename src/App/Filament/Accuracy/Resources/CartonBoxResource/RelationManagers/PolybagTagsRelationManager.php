<?php

namespace App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PolybagTagsRelationManager extends RelationManager
{
    protected static string $relationship = 'polybagTags';


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('tag')
                            ->label('Tag'),

                    ])->columns(2),
            ]);
    }
    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === 'RATIO' || $ownerRecord->type === 'RATIO SET';
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tag')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('tag')
                    ->label('Tag'),
                Tables\Columns\TextColumn::make('attributable.size')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('attributable.color')
                    ->label('Color'),
                Tables\Columns\TextColumn::make('createdBy.name')
                    ->label('Scanned By'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Scanned At'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
