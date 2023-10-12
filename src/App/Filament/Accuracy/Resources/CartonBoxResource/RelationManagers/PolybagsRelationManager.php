<?php

namespace App\Filament\Accuracy\Resources\CartonBoxResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PolybagsRelationManager extends RelationManager
{
    protected static string $relationship = 'polybags';

    protected static ?string $title = 'Polybags';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('General Information')
                    ->schema([
                        Forms\Components\TextInput::make('polybag_code')
                            ->label('Polybag Code'),
                    ])->columns(2),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('polybag_code')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->label('#'),
                Tables\Columns\TextColumn::make('polybag_code')
                    ->label('Polybag Code'),
                Tables\Columns\TextColumn::make('box.color')
                    ->hidden(fn (RelationManager $livewire): bool => $livewire->ownerRecord->type === 'RATIO' || $livewire->ownerRecord->type === 'MIX')
                    ->label('Color'),
                Tables\Columns\TextColumn::make('box.size')
                    ->hidden(fn (RelationManager $livewire): bool => $livewire->ownerRecord->type === 'RATIO' || $livewire->ownerRecord->type === 'MIX')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('box.type')
                    ->label('Type'),
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
