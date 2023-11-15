<?php

namespace App\Filament\Purchase\Resources\ApprovalRequestResource\Pages;

use App\Filament\Purchase\Resources\ApprovalRequestResource;
use Domain\Purchases\Models\ApprovalRequest;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListApprovalRequests extends ListRecords
{
    protected static string $resource = ApprovalRequestResource::class;
    use HasPreviewModal;
    // protected function getPreviewModalUrl(): ?string
    // {
    //     $record = $this->previewModalData['record'];

    //     return route('request.document.report', ['record' => $record->approvable]);
    // }
    protected function getPreviewModalView(): ?string
    {
        // This corresponds to resources/views/posts/preview.blade.php
        return 'purchase.reports.request';
    }
    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'detail';
    }
    protected function mutatePreviewModalData(array $data): array
    {
        return ['detail' => $data['record']];
    }
    // protected function getHeaderActions(): array
    // {
    //     return [
    //         Actions\CreateAction::make(),
    //     ];
    // }
    public function getTabs(): array
    {
        return [
            // 'admin' => Tab::make()

            //     ->icon('heroicon-o-clipboard-document-list')
            //     ->badge(ApprovalRequest::query()->whereBelongsTo(auth()->user())->count())
            //     ->modifyQueryUsing(fn (Builder $query) => $query),
            'need' => Tab::make()
                ->icon('heroicon-o-clipboard-document-list')
                ->badge(ApprovalRequest::query()->whereBelongsTo(auth()->user())->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBelongsTo(auth()->user())),
            'approved' => Tab::make()
                ->icon('tabler-clipboard-check')
                ->badge(ApprovalRequest::query()->whereBelongsTo(auth()->user())->where('action', 'Approve')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBelongsTo(auth()->user())->where('action', 'Approve')),
            'rejected' => Tab::make()
                ->icon('tabler-clipboard-x')
                ->badge(ApprovalRequest::query()->whereBelongsTo(auth()->user())->where('action', 'Reject')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereBelongsTo(auth()->user())->where('action', 'Reject')),

        ];
    }
}
