<?php

namespace App\Filament\Purchase\Resources\RequestResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApprovalHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'approvalHistories';

    public function isReadOnly(): bool
    {
        return true;
    }
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('action')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('action')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('User')),
                Tables\Columns\TextColumn::make('user.approvalUser.level')
                    ->label(__('Level')),
                Tables\Columns\TextColumn::make('user.approvalUser.department.name')
                    ->label(__('Department')),
                Tables\Columns\TextColumn::make('action'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label(__('Action Time')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
                Tables\Actions\AssociateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DissociateAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
