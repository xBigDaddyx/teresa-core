<?php

namespace App\Filament\Accuracy\Resources\CartonBoxResource\Pages;

use App\Filament\Accuracy\Resources\CartonBoxResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCartonBoxes extends ListRecords
{
    protected static string $resource = CartonBoxResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
