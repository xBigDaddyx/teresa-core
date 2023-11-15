<?php

namespace App\Filament\Purchase\Resources\RequestResource\Pages;

use App\Filament\Purchase\Resources\RequestResource;
use Domain\Purchases\Models\Request;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Pboivin\FilamentPeek\Pages\Actions\PreviewAction;

class EditRequest extends EditRecord
{
    use HasPreviewModal;
    protected static string $resource = RequestResource::class;
    protected function getPreviewModalView(): ?string
    {
        // This corresponds to resources/views/posts/preview.blade.php
        return 'purchase.reports.request';
    }
    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'detail';
    }
    protected function getHeaderActions(): array
    {
        return [
            PreviewAction::make(),
            Actions\DeleteAction::make()
                ->hidden(fn (Request $record): bool => $record->requestItems->count() > 0),
            Actions\ForceDeleteAction::make()
                ->hidden(fn (Request $record): bool => $record->requestItems->count() > 0),
            Actions\RestoreAction::make(),
        ];
    }
}
