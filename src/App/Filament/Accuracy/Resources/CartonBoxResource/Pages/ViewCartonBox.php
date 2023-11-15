<?php

namespace App\Filament\Accuracy\Resources\CartonBoxResource\Pages;

use App\Filament\Accuracy\Resources\CartonBoxResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCartonBox extends ViewRecord
{
    protected static string $resource = CartonBoxResource::class;
    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }
    //protected static string $view = 'packing::admin.pages.view.carton-box';
}
