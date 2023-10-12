<?php

namespace App\Filament\Accuracy\Resources\PackingListResource\RelationManagers;

use App\Filament\Accuracy\Resources\CartonBoxResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CartonBoxesRelationManager extends RelationManager
{
    protected static string $relationship = 'cartonBoxes';
    protected bool $allowsDuplicates = false;
    protected static ?string $title = 'Boxes';
    public function form(Form $form): Form
    {
        return CartonBoxResource::form($form);
        // return $form
        //     ->schema([

        //         Forms\Components\Section::make('General Information')
        //             ->schema([
        //                 Forms\Components\TextInput::make('box_code')
        //                     ->helperText('Should be carton box barcode.')
        //                     ->hint('Carton Box Barcode')
        //                     ->hintIcon('tabler-barcode')
        //                     ->hintColor('primary')
        //                     ->label('Box Code'),

        //                 Forms\Components\TextInput::make('carton_number')
        //                     ->numeric()
        //                     ->label('Carton Number')
        //                     ->helperText('Define your carton numbering.')
        //                     ->hint('Carton Box Numbering')
        //                     ->hintIcon('tabler-numbers')
        //                     ->hintColor('primary'),
        //                 Forms\Components\Select::make('type')
        //                     ->reactive()
        //                     ->options([
        //                         'SOLID' => 'SOLID',
        //                         'MIX' => 'MIX',
        //                         'RATIO' => 'RATIO',
        //                     ])
        //                     ->required()
        //                     // ->hidden(fn (RelationManager $livewire): bool => $livewire->ownerRecord->type === 'RATIO' || $livewire->ownerRecord->type ==='RATIO SET')
        //                     ->default('SOLID')
        //                     ->helperText('type of box items')
        //                     ->hint('Items Type')
        //                     ->hintIcon('tabler-Forms\Components\selector')
        //                     ->hintColor('primary')
        //                     ->label('Type'),
        //                 Forms\Components\TextInput::make('size')
        //                     ->reactive()
        //                     ->hidden(function (RelationManager $livewire, $state, callable $get, callable $set) {
        //                         $type = $get('type');
        //                         if ($type !== null) {
        //                             if ($type === 'SOLID') {
        //                                 return false;
        //                             }

        //                             return true;
        //                         } elseif ($livewire->ownerRecord->type === 'RATIO') {
        //                             return true;
        //                         }
        //                     })
        //                     ->helperText('Fill size attribute of box items, or keep it blank.')
        //                     ->hint('For SOLID only!')
        //                     ->hintIcon('tabler-ruler')
        //                     ->hintColor('danger')
        //                     ->label('Size'),
        //                 Forms\Components\TextInput::make('color')
        //                     ->hidden(function (RelationManager $livewire, $state, callable $get, callable $set) {
        //                         $type = $get('type');
        //                         if ($type !== null) {
        //                             if ($type === 'SOLID') {
        //                                 return false;
        //                             }

        //                             return true;
        //                         } elseif ($livewire->ownerRecord->type === 'RATIO') {
        //                             return true;
        //                         }
        //                     })
        //                     ->helperText('Fill color attribute of box items, or keep it blank.')
        //                     ->hint('For SOLID only!')
        //                     ->hintIcon('tabler-color-swatch')
        //                     ->hintColor('danger')
        //                     ->label('Color'),
        //                 Forms\Components\TextInput::make('quantity')
        //                     ->helperText('Fill quantity attribute of box items, or keep it blank.')
        //                     ->hint('Quantity Items')
        //                     ->hintIcon('tabler-calculator')
        //                     ->hintColor('primary')
        //                     ->required()
        //                     ->numeric()
        //                     ->label('Quantity'),

        //                 Forms\Components\Checkbox::make('is_completed')
        //                     ->label('Completed')
        //                     ->hiddenOn('create'),
        //             ])->columns(2),
        //     ]);
    }

    public function table(Table $table): Table
    {
        return CartonBoxResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data, RelationManager $livewire): array {
                        if ($livewire->ownerRecord->type === 'RATIO') {
                            $data['type'] = 'RATIO';
                        }

                        return $data;
                    }),
            ]);
        // return $table
        //     ->recordTitleAttribute('box_code')
        //     ->columns([

        //         Tables\Columns\TextColumn::make('id')
        //             ->label('Box ID'),
        //         Tables\Columns\TextColumn::make('box_code')
        //             ->label('Box Code'),
        //         Tables\Columns\TextColumn::make('carton_number')
        //             ->label('Carton Number'),
        //         Tables\Columns\TextColumn::make('carton_number')
        //             ->label('Carton Number'),
        //         Tables\Columns\TextColumn::make('size')
        //             ->hidden(fn (RelationManager $livewire): bool => $livewire->ownerRecord->type === 'RATIO' || $livewire->ownerRecord->type === 'RATIO SET')
        //             ->label('Size'),
        //         Tables\Columns\TextColumn::make('color')
        //             ->hidden(fn (RelationManager $livewire): bool => $livewire->ownerRecord->type === 'RATIO' || $livewire->ownerRecord->type === 'RATIO SET')
        //             ->label('Color'),
        //         Tables\Columns\TextColumn::make('quantity')
        //             ->label('Quantity'),
        //         Tables\Columns\TextColumn::make('type')
        //             ->label('Type'),
        //         Tables\Columns\TextColumn::make('description')
        //             ->hidden(fn (RelationManager $livewire): bool => $livewire->ownerRecord->type === 'RATIO' || $livewire->ownerRecord->type === 'RATIO SET')
        //             ->label('Box Info'),
        //         Tables\Columns\IconColumn::make('is_completed')
        //             ->boolean()
        //             ->trueIcon('tabler-clipboard-check')
        //             ->falseIcon('tabler-clipboard-x')
        //             ->label('Completed'),
        //     ])
        //     ->filters([
        //         //
        //     ])
        //     ->headerActions([
        //         Tables\Actions\CreateAction::make()
        //             ->mutateFormDataUsing(function (array $data, RelationManager $livewire): array {
        //                 if ($livewire->ownerRecord->type === 'RATIO') {
        //                     $data['type'] = 'RATIO';
        //                 }

        //                 return $data;
        //             }),
        //     ])
        //     ->actions([
        //         Tables\Actions\EditAction::make(),
        //         Tables\Actions\DeleteAction::make(),
        //     ])
        //     ->bulkActions([
        //         Tables\Actions\BulkActionGroup::make([
        //             Tables\Actions\DeleteBulkAction::make(),
        //         ]),
        //     ]);
    }
}
