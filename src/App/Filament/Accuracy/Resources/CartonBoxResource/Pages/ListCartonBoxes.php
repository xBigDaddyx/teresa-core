<?php

namespace App\Filament\Accuracy\Resources\CartonBoxResource\Pages;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Filament\Accuracy\Resources\CartonBoxResource;
use Domain\Accuracies\Models\CartonBox;
use Filament\Actions;
use Filament\Actions\ActionGroup;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ListCartonBoxes extends ListRecords
{
    protected static string $resource = CartonBoxResource::class;
    public function getTabs(): array
    {
        return [
            'all' => Tab::make()
                ->icon('heroicon-o-clipboard-document-list'),
            'completed' => Tab::make()
                ->icon('heroicon-o-clipboard-document-check')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_completed', true)),
            'outstanding' => Tab::make()
                ->icon('heroicon-o-clipboard-document')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_completed', false)),
            'inspection' => Tab::make()
                ->badgeColor('danger')
                ->badge(CartonBox::query()->where('in_inspection', true)->count())
                ->icon('heroicon-o-document-magnifying-glass')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('in_inspection', true)),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [

            Actions\CreateAction::make(),


        ];
    }
}
