<?php

namespace App\Filament\Accuracy\Resources\CartonBoxResource\Pages;

use App\Filament\Accuracy\Resources\CartonBoxResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCartonBox extends EditRecord
{
    protected static string $resource = CartonBoxResource::class;
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
