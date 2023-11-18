<?php

namespace App\Filament\Purchase\Resources\RequestItemResource\Pages;

use App\Filament\Purchase\Resources\RequestItemResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRequestItem extends EditRecord
{
    protected static string $resource = RequestItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
