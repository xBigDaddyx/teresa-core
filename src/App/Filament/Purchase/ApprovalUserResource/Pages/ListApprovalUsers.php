<?php

namespace App\Filament\Purchase\ApprovalUserResource\Pages;

use App\Filament\Purchase\Resources\ApprovalUserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovalUsers extends ListRecords
{
    protected static string $resource = ApprovalUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
