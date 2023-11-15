<?php

namespace App\Filament\Purchase\Resources\ProductResource\Pages;

use App\Filament\Purchase\Resources\ProductResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class ListProducts extends ListRecords
{
    use HasPreviewModal;
    protected static string $resource = ProductResource::class;
    protected function getPreviewModalView(): ?string
    {
        // This corresponds to resources/views/posts/preview.blade.php
        return 'purchase.pages.product';
    }
    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'detail';
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
