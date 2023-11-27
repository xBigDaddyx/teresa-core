<?php

namespace App\Filament\Purchase\ApprovalRequestResource\Pages;

use App\Filament\Purchase\Resources\ApprovalRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalRequest extends EditRecord
{
    protected static string $resource = ApprovalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
