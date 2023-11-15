<?php

namespace App\Filament\Kanban\Resources\PlanResource\Pages;

use App\Filament\Kanban\Resources\PlanQueueResource;
use Filament\Actions;
use Filament\Pages\Page;
use Filament\Resources\Pages\ListRecords;

class ListPlanQueues extends ListRecords
{
    protected static string $resource = PlanQueueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
