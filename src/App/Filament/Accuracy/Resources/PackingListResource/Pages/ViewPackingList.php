<?php

namespace App\Filament\Accuracy\Resources\PackingListResource\Pages;

use Domain\Accuracies\Models\PackingList;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPackingList extends ViewRecord
{
    protected static string $resource = PackingList::class;
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
}
