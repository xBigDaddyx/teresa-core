<?php

namespace App\Filament\Accuracy\Resources\PackingListResource\Pages;

use App\Filament\Accuracy\Resources\PackingListResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPackingList extends EditRecord
{
    protected static string $resource = PackingListResource::class;
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
