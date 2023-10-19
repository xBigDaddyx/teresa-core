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
use Livewire\Livewire;

class CartonBoxAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'cartonBoxAttributes';
    protected static ?string $modelLabel = 'Attributes';
    protected static ?string $title = 'Attributes';
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Placeholder::make('type')
                    ->content(fn (RelationManager  $livewire): string => $livewire->getOwnerRecord()->type)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('size')
                    ->helperText('Should be the size of attribute')
                    ->hint('Size Attribute')
                    ->hintIcon('tabler-ruler-measure')
                    ->hintColor('primary'),
                Forms\Components\TextInput::make('tag')
                    ->helperText('Should be the tag of attribute')
                    ->hint('Tag Attribute')
                    ->hintIcon('tabler-tag')
                    ->hintColor('primary'),
                Forms\Components\TextInput::make('color')
                    ->helperText('Should be the name of attribute')
                    ->hint('Color Attribute')
                    ->hintIcon('tabler-color-swatch')
                    ->hintColor('primary'),
                Forms\Components\TextInput::make('quantity')
                    ->numeric()
                    ->helperText('Should be the quantities of attribute')
                    ->hint('Quantity Attribute')
                    ->hintIcon('tabler-calculator')
                    ->hintColor('primary'),
            ]);
    }

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord->type === 'MIX' || $ownerRecord->type === 'RATIO';
    }
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID Attribute'),
                Tables\Columns\TextColumn::make('tag')
                    ->label('Tag'),
                Tables\Columns\TextColumn::make('size')
                    ->label('Size'),
                Tables\Columns\TextColumn::make('color')
                    ->label('Color'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('New Attribute')
                    ->visible(function (RelationManager $livewire) {
                        if (auth()->user()->can('carton-boxes-attributes.create')) {
                            if ($livewire->ownerRecord->type === 'MIX') {
                                if ($livewire->ownerRecord->cartonBoxAttributes->count() < $livewire->ownerRecord->quantity) {
                                    return true;
                                }
                            } else if ($livewire->ownerRecord->type === 'RATIO') {
                                return true;
                            }
                        }
                        return false;
                    }),
                //->visible(fn (RelationManager $livewire): bool => auth()->user()->can('carton-boxes-attributes.create') && $livewire->ownerRecord->type === 'MIX' && $livewire->ownerRecord->cartonBoxAttributes->count() < $livewire->ownerRecord->quantity),
                Tables\Actions\AssociateAction::make()
                    ->visible(function (RelationManager $livewire) {
                        if (auth()->user()->can('carton-boxes-attributes.associate')) {
                            if ($livewire->ownerRecord->type === 'MIX') {
                                if ($livewire->ownerRecord->cartonBoxAttributes->count() < $livewire->ownerRecord->quantity) {
                                    return true;
                                }
                            } else if ($livewire->ownerRecord->type === 'RATIO') {
                                return true;
                            }
                        }
                        return false;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes-attributes.edit')),
                Tables\Actions\DissociateAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes-attributes.dissociate')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()->can('carton-boxes-attributes.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('carton-boxes-attributes.dissociateBulk')),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('carton-boxes-attributes.deleteBulk')),
                ]),
            ]);
    }
}
