<?php

namespace App\Filament\Kanban\Resources\SewingResource\Pages;

use App\Filament\Kanban\Resources\SewingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSewing extends EditRecord
{
    protected static string $resource = SewingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
