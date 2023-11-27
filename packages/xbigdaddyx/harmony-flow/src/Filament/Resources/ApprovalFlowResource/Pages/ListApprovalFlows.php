<?php

namespace Xbigdaddyx\HarmonyFlow\Filament\Resources\ApprovalFlowResource\Pages;

use Xbigdaddyx\HarmonyFlow\Filament\Resources\ApprovalFlowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalFlows extends ListRecords
{
    protected static string $resource = ApprovalFlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
