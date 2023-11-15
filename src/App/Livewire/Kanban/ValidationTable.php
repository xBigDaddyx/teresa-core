<?php

namespace App\Livewire\Kanban;

use Domain\Accuracies\Models\CartonBox;
use Domain\Accuracies\Models\Polybag;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Filament\Tables;
use Filament\Tables\Grouping\Group;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Action;
use Filament\Actions\Contracts\HasActions;

class ValidationTable extends Component
{

    public CartonBox $carton;
    public Polybag $polybag;

    public function delete(Polybag $polybag)
    {
        $polybag->delete();
    }
    // public function deleteAction(): Action
    // {
    //     return Action::make('delete')
    //         ->requiresConfirmation()
    //         ->action(function (array $arguments) {
    //             $polybag = Polybag::find($arguments['polybag']);

    //             $polybag?->delete();
    //         });
    // }
    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->relationship(fn () => $this->carton->polybags())
    //         ->inverseRelationship('box')
    //         ->columns([
    //             TextColumn::make('id')
    //                 ->label(__('ID')),
    //             TextColumn::make('polybag_code')
    //                 ->searchable()
    //                 ->label(__('Polybag Code')),
    //             TextColumn::make('cartonBox.box_code')
    //                 ->label(__('Box Code')),
    //             TextColumn::make('scannedBy.name')
    //                 ->label(__('Scanned By')),
    //             TextColumn::make('created_at')
    //                 ->dateTime()
    //                 ->label(__('Scanned At')),
    //         ])
    //         ->groups([
    //             Group::make('cartonBox.box_code')
    //         ])
    //         ->paginated([10, 25, 50, 100, 'all'])
    //         ->filters([
    //             // ...
    //         ])
    //         ->actions([
    //             Tables\Actions\DeleteAction::make(),
    //         ])
    //         ->bulkActions([
    //             // ...
    //         ])->striped()->poll('10s');
    // }
    public function render()
    {
        return view('livewire.kanban.validation-table');
    }
}
