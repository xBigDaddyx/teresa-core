<?php

namespace App\Filament\Kanban\Resources\ShiftResource\Pages;

use App\Filament\Kanban\Resources\ShiftResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageShifts extends ManageRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
