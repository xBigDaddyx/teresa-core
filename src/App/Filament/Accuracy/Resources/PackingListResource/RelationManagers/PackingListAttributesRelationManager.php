<?php

namespace App\Filament\Accuracy\Resources\PackingListResource\RelationManagers;

use Domain\Accuracies\Models\CartonBoxAttribute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Livewire;

class PackingListAttributesRelationManager extends RelationManager
{
    protected static string $relationship = 'packingListAttributes';
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
                Tables\Actions\Action::make('sync')
                    ->tooltip('Synchronization of carton box attributes')
                    ->icon('heroicon-m-arrow-path')
                    ->hidden(function (RelationManager $livewire) {
                        $cartonBoxes = $livewire->ownerRecord->whereHas('cartonBoxes', function (Builder $query) use ($livewire) {
                            $query->where('is_completed', true)->where('packing_list_id', $livewire->ownerRecord->id);
                        })->count();

                        if ($cartonBoxes > 0) {
                            return true;
                        }
                        return false;
                    })
                    ->label('Sync')
                    ->action(function (RelationManager $livewire) {
                        $pl_attributes = $livewire->ownerRecord->packingListAttributes;
                        foreach ($pl_attributes as $attribute) {
                            foreach ($livewire->ownerRecord->cartonBoxes as $carton) {
                                CartonBoxAttribute::updateOrCreate(
                                    ['carton_box_id' => $carton->id, 'tag' => $attribute->tag],
                                    [
                                        'size' => $attribute->size,
                                        'color' => $attribute->color,
                                        'quantity' => $attribute->quantity,
                                        'type' => $attribute->type,
                                    ]
                                );
                            }
                        }
                        return Notification::make()
                            ->success()
                            ->title('Sync attributes done')
                            ->body('Attributes successfully synced with all carton boxes.')
                            ->send();
                    }),
                Tables\Actions\CreateAction::make()
                    ->label('New Attribute')
                    ->visible(function (RelationManager $livewire) {
                        if (auth()->user()->can('packing-lists-attributes.create')) {
                            if ($livewire->ownerRecord->type === 'MIX') {
                                if ($livewire->ownerRecord->packingListAttributes->count() < $livewire->ownerRecord->quantity) {
                                    return true;
                                }
                            } else if ($livewire->ownerRecord->type === 'RATIO') {
                                return true;
                            }
                        }
                        return false;
                    }),
                //->visible(fn (RelationManager $livewire): bool => auth()->user()->can('packing-lists-attributes.create') && $livewire->ownerRecord->type === 'MIX' && $livewire->ownerRecord->packingListAttributes->count() < $livewire->ownerRecord->quantity),
                // Tables\Actions\AssociateAction::make()
                //     ->visible(function (RelationManager $livewire) {
                //         if (auth()->user()->can('packing-lists-attributes.associate')) {
                //             if ($livewire->ownerRecord->type === 'MIX') {
                //                 if ($livewire->ownerRecord->packingListAttributes->count() < $livewire->ownerRecord->quantity) {
                //                     return true;
                //                 }
                //             } else if ($livewire->ownerRecord->type === 'RATIO') {
                //                 return true;
                //             }
                //         }
                //         return false;
                //     }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (): bool => auth()->user()->can('packing-lists-attributes.edit')),
                // Tables\Actions\DissociateAction::make()
                //     ->visible(fn (): bool => auth()->user()->can('packing-lists-attributes.dissociate')),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (): bool => auth()->user()->can('packing-lists-attributes.delete')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DissociateBulkAction::make()
                    //     ->visible(fn (): bool => auth()->user()->can('packing-lists-attributes.dissociateBulk')),
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn (): bool => auth()->user()->can('packing-lists-attributes.deleteBulk')),
                ]),
            ]);
    }
}
