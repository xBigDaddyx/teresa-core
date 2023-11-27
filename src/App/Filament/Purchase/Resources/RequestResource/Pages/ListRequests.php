<?php

namespace App\Filament\Purchase\Resources\RequestResource\Pages;

use App\Filament\Purchase\Resources\RequestResource;
use Domain\Purchases\Models\Request;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ListRecords;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListRequests extends ListRecords
{
    use HasPreviewModal;
    protected function getPreviewModalView(): ?string
    {
        // This corresponds to resources/views/posts/preview.blade.php
        return 'purchase.reports.request';
    }
    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'detail';
    }
    protected static string $resource = RequestResource::class;
    // public function getTabs(): array
    // {
    //     if (Auth::guard('ldap')->user()->hasRole('purchase-approver')) {
    //         $departments = Auth::guard('ldap')->user()->purchaseDepartments;
    //         return [
    //             'requested' => Tab::make()
    //                 ->icon('heroicon-o-clipboard-document-list')
    //                 // ->badge(DB::table('requests')->whereIn('department_id', $user->purchaseDepartment))
    //                 ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('department_id', $departments->toAarray())),
    //         ];
    //     }
    //     return [

    //         'draft' => Tab::make()
    //             ->icon('tabler-pencil')
    //             ->badge(Request::query()->where('created_by', auth()->user()->id)->where('is_submited', false)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->user()->id)->where('is_submited', false)),
    //         'submited' => Tab::make()
    //             ->icon('tabler-file-export')
    //             ->badge(Request::query()->where('created_by', auth()->user()->id)->where('is_submited', true)->where('is_processed', false)->count())
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->user()->id)->where('is_submited', true)->where('is_processed', false)),
    //         'processed' => Tab::make()
    //             ->icon('tabler-arrow-autofit-height')
    //             ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->user()->id)->where('is_processed', true)),

    //     ];
    // }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
