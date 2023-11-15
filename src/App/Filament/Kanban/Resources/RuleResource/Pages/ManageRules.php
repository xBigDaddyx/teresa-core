<?php

namespace App\Filament\Kanban\Resources\RuleResource\Pages;

use App\Filament\Kanban\Resources\RuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRules extends ManageRecords
{
    protected static string $resource = RuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
