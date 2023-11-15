<?php

namespace App\Filament\Kanban\Resources\PlanQueueResource\Pages;

use App\Filament\Kanban\Resources\PlanQueueResource;
use Filament\Resources\Pages\ManageRecords;
use Filament\Actions;

class ManageQueue extends ManageRecords
{
    protected static string $resource = PlanQueueResource::class;
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
