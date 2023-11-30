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
use Illuminate\Database\Eloquent\Collection;
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
    public function getTabs(): array
    {
        if (Auth::guard('ldap')->user()->hasRole(['purchase-approver'])) {
            $departments = Auth::guard('ldap')->user()->purchaseDepartments;
            return [
                'requests' => Tab::make()
                    ->icon('tabler-clipboard-list')
                    // ->badge(DB::table('requests')->whereIn('department_id', $user->purchaseDepartment))
                    ->modifyQueryUsing(function (Builder $query) {
                        $departments = auth('ldap')->user()->departments;
                        if ($departments->count() > 0) {
                            $collection = new Collection();
                            foreach ($departments as $dept) {
                                $collection->push($dept->id);
                            }
                            $query->whereIn('department_id', $collection->toArray())->whereHas('approvalStatus', function (Builder $q) {
                                $q->where('status', 'submitted');
                            });
                        }
                        return $query;
                    }),
                'approved' => Tab::make()
                    ->icon('tabler-checks')
                    // ->badge(DB::table('requests')->whereIn('department_id', $user->purchaseDepartment))
                    ->modifyQueryUsing(function (Builder $query) {
                        $departments = auth('ldap')->user()->departments;
                        if ($departments->count() > 0) {
                            $collection = new Collection();
                            foreach ($departments as $dept) {
                                $collection->push($dept->id);
                            }
                            $query->whereIn('department_id', $collection->toArray())->whereHas('approvals', function (Builder $q) {
                                $q->where('approval_action', 'Approved')->where('user_id', auth('ldap')->user()->id);
                            });
                        }
                        return $query;
                    }),
                'rejected' => Tab::make()
                    ->icon('tabler-x')
                    // ->badge(DB::table('requests')->whereIn('department_id', $user->purchaseDepartment))
                    ->modifyQueryUsing(function (Builder $query) {
                        $departments = auth('ldap')->user()->departments;
                        if ($departments->count() > 0) {
                            $collection = new Collection();
                            foreach ($departments as $dept) {
                                $collection->push($dept->id);
                            }
                            $query->whereIn('department_id', $collection->toArray())->whereHas('approvals', function (Builder $q) {
                                $q->where('approval_action', 'Rejected')->where('user_id', auth('ldap')->user()->id);
                            });
                        }
                        return $query;
                    }),
            ];
        }
        return [

            'draft' => Tab::make()
                ->icon('tabler-pencil')
                ->badge(Request::query()->where('created_by', auth()->user()->id)->whereHas('approvals', fn (Builder $que) => $que->where('approval_action', 'Created'))->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->user()->id)->whereHas('approvals', fn (Builder $que) => $que->where('approval_action', 'Created'))),
            'submited' => Tab::make()
                ->icon('tabler-file-export')
                ->badge(Request::query()->where('created_by', auth()->user()->id)->whereHas('approvals', fn (Builder $que) => $que->where('approval_action', 'Submitted'))->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->user()->id)->whereHas('approvals', fn (Builder $que) => $que->where('approval_action', 'Submitted'))),
            'processed' => Tab::make()
                ->icon('tabler-arrow-autofit-height')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('created_by', auth()->user()->id)->where('is_processed', true)),

        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
