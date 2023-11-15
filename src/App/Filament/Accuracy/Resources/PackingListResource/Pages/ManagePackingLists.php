<?php

namespace App\Filament\Accuracy\Resources\PackingListResource\Pages;

use App\Filament\Accuracy\Resources\PackingListResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManagePackingLists extends ManageRecords
{
    protected static string $resource = PackingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
