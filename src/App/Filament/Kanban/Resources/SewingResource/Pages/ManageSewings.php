<?php

namespace App\Filament\Kanban\Resources\SewingResource\Pages;

use App\Filament\Kanban\Resources\SewingResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Get;
use Filament\Resources\Pages\Concerns\HasWizard;

class ManageSewings extends ManageRecords
{
    
    protected static string $resource = SewingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
