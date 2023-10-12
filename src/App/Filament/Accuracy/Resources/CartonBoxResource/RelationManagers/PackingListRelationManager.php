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

class PackingListRelationManager extends RelationManager
{
    protected static string $relationship = 'packingList';
    protected static ?string $inverseRelationship = 'boxes';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->isUnlocked();
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('po')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('po')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('po')
                    ->searchable()
                    ->sortable()
                    ->label('PO'),
                Tables\Columns\TextColumn::make('buyer.name')
                    ->searchable()
                    ->sortable()
                    ->label('Buyer'),
                Tables\Columns\TextColumn::make('buyer.country')
                    ->searchable()
                    ->sortable()
                    ->label('Buyer Country'),
                Tables\Columns\TextColumn::make('style_no')
                    ->searchable()
                    ->sortable()
                    ->label('Style'),
                Tables\Columns\TextColumn::make('contract_no')
                    ->searchable()
                    ->sortable()
                    ->label('Contract'),
                Tables\Columns\TextColumn::make('batch')
                    ->searchable()
                    ->sortable()
                    ->label('Batch'),
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->label('Description'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Created'),
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
