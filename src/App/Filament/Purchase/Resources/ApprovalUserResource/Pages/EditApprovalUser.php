<?php

namespace App\Filament\Purchase\Resources\ApprovalUserResource\Pages;

use App\Filament\Purchase\Resources\ApprovalUserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditApprovalUser extends EditRecord
{
    protected static string $resource = ApprovalUserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
