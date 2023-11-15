<?php

namespace App\Filament\Purchase\Resources\StateResource\Pages;

use App\Filament\Purchase\Resources\StateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewState extends ViewRecord
{
    protected static string $resource = StateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
