<?php

namespace App\Filament\Accuracy\Resources\PackingListResource\Pages;

use App\Filament\Accuracy\Resources\PackingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPackingLists extends ListRecords
{
    protected static string $resource = PackingListResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
