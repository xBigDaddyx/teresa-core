<?php

namespace Domain\Purchases\Traits;

use App\Jobs\ProcessApproval;
use Domain\Purchases\Models\ApprovalFlow;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

trait HasApproval
{

    public function getPersonInCharge(Model $department, $level)
    {

        return User::whereHas('approvalUser', function (Builder $query) use ($department, $level) {
            $query->where('department_id', $department->id)->where('level', $level)->whereBelongsTo(Filament::getTenant());
        })->first();
    }

    public function getSubmittedColumn()
    {
        return defined(static::class . '::IS_SUBMITED') ? static::IS_SUBMITED : 'is_submited';
    }
    public function getProcessedColumn()
    {
        return defined(static::class . '::IS_PROCESSED') ? static::IS_PROCESSED : 'is_processed';
    }

    public function submit()
    {
        return ProcessApproval::dispatch($this, null, 'Submit', auth()->user());
        // if ($this->is_submited === false) {
        //     $this->setConnection('teresa_purchase');
        //     $query = $this->setKeysForSaveQuery($this->newModelQuery());
        //     $columns = [$this->getSubmittedColumn() => true];
        //     $query->update($columns);
        //     $this->syncOriginalAttributes(array_keys($columns));

        //     if (str_contains($this->table, 'PR') || str_contains($this->table, 'requests')) {
        //         $type = 'PR';
        //     } else if (str_contains($this->table, 'PO') || str_contains($this->table, 'orders')) {
        //         $type = 'PO';
        //     }
        //     $flows = $this->getApprovalFlow();
        //     $first_flow = $flows->where('type', $type)->where('order', 1)->first();
        //     $department = $this->department;
        //     $person_in_charge = User::whereHas('purchaseDepartments', function (Builder $query) use ($department, $first_flow) {
        //         $query->where('department_id', $department->id)->where('role', $first_flow->level)->whereBelongsTo(Filament::getTenant());
        //     })->first();
        //     return ApprovalRequest::create([
        //         'status' => 'Submitted',
        //         'approval_flow_id' => $first_flow->id,
        //         'approvable_id' => $this->id,
        //         'approvable_type' => 'Domain\Purchases\Models\Request',
        //         'last_status' => null,
        //         'user_id' => $person_in_charge->id,
        //         'action' => 'Submit',
        //         'company_id' => auth()->user()->company->id,
        //         'created_by' => auth()->user()->id,
        //     ]);
        // }
    }
    public function approve()
    {
        return ProcessApproval::dispatch($this, null, 'Submit', auth()->user());
        // $next = $this->getNextApproval();
        // $lastApproval = $this->getApprovalRecords();
        // $create = ApprovalRequest::create([
        //     'status' => 'Approved',
        //     'approvable_id' => $lastApproval->approvable_id,
        //     'approvable_id' => $lastApproval->approvable_type,
        //     'last_status' => $lastApproval->status,
        //     'user_id' => $next->next_person_charge,
        //     'action' => 'Approve',
        //     'company_id' => auth()->user()->company->id,
        //     'created_by' => auth()->user()->id,
        // ]);
        // if ($create) {
        //     $lastApproval->delete();
        // }
    }
    public function getApprovalRecords()
    {
        $approvals = ApprovalRequest::with('approvalFlow')->where('approvable_id', $this->id)->orderBy('created_at', 'DESC')->first();
        return $approvals;
    }
    public function approvalLastStatus()
    {
        $approvals = $this->getApprovalRecords();
        if (empty($approvals) && $this->is_submited === false) {
            return collect([
                'status' => 'Draft',
                'message' => 'This request is on draft'
            ]);
        }

        if ($approvals->status !== 'Rejected' || $approvals->status !== 'Approved') {
            return collect([
                'status' => 'Pending',
                'message' => 'Pending to ' . $approvals->user->name,
                // 'next_level_person' => $this->getPersonInCharge($approvals->approvable->department, $next_flow->level)->name ?? 'Unknown',
                // 'next_level' => $next_flow->level,
            ]);
        }
        return collect([
            'status' => 'Submited',
            'message' => 'This request already submited'
        ]);
    }
    public function getApprovalFlow()
    {
        if (str_contains($this->table, 'PR') || str_contains($this->table, 'requests')) {
            return ApprovalFlow::where('type', 'PR')->whereBelongsTo(Filament::getTenant())->orderBy('order', 'DESC')->get();
        } else if (str_contains($this->table, 'PO') || str_contains($this->table, 'orders')) {
            return ApprovalFlow::where('type', 'PO')->whereBelongsTo(Filament::getTenant())->orderBy('order', 'DESC')->get();
        }
    }
    // public function waiting(){

    // }
    public function getNamespace()
    {
        return get_class($this);
    }
    public function getNextApproval()
    {
        $approvals = $this->getApprovalRecords();

        $flows = $this->getApprovalFlow();
        $order = (int)$approvals->approvalFlow->order;
        if ($order === 0) {
            $order = $order + 2;
            $next = $flows->where('order',  $order)->first();
        } else {
            $order = $order + 1;
            $next = $flows->where('order', $approvals->approvalFlow->order + 1)->first();
        }

        if ($next) {
            if ($next->is_skipable === true) {
                // has person in charge or not
                $incharge = $this->getPersonInCharge($approvals->approvable->department, $next->level) ?? 'Unknown';

                if ($incharge === 'Unknown') {
                    if ($next->is_last_stage === false) {
                        // go to next level
                        $next = $flows->where('order', $order + 1)->first();
                        $incharge = $this->getPersonInCharge($approvals->approvable->department, $next->level) ?? 'Unknown';
                        if ($next->is_last_stage === true) {
                            if ($incharge === 'Unknown') {
                                return collect([
                                    'status' => 'Missing',
                                    'message' => 'Next stage, is missing person in charge then approval process will on hold after this stage',
                                    'waiting' => $approvals->user->name,
                                    'on_stage' => $approvals->approvalFlow->level,
                                    'next_stage' => $next->level,
                                    'next_stage_id' => $next->id,
                                    'next_person_charge' => 0,
                                    'next_person' => 'Missing',
                                ]);
                            }
                            if ($next->level === 'Purchasing') {
                                return collect([
                                    'status' => 'Completed',
                                    'message' => 'Approval process is completed',
                                    'waiting' => 'Completed',
                                    'on_stage' => $approvals->approvalFlow->level,
                                    'next_stage' => $next->level,
                                    'next_stage_id' => $next->id,
                                    'next_person_charge' => $incharge->id,
                                    'next_person' => $incharge->name,
                                ]);
                            }
                            return collect([
                                'status' => $approvals->status,
                                'message' => 'Still waiting for action on this stage, if approved then approval process going to next stage',
                                'waiting' => $approvals->user->name,
                                'on_stage' => $approvals->approvalFlow->level,
                                'next_stage' => $next->level,
                                'next_stage_id' => $next->id,
                                'next_person_charge' => $incharge->id,
                                'next_person' => $incharge->name,
                            ]);
                        }
                    }

                    return collect([
                        'status' => $approvals->status,
                        'message' => 'If ' . $approvals->approvalFlow->level . ' approved, then approval process is complete',
                        'waiting' => $approvals->user->name,
                        'on_stage' => $approvals->approvalFlow->level,
                        'next_stage' => 'Complete',
                        'next_stage_id' => 'Completed',
                        'next_person_charge' => 0,
                        'next_person' => 'Completed',
                    ]);
                }
            }
            $incharge = $this->getPersonInCharge($approvals->approvable->department, $next->level) ?? 'Unknown';
            if ($incharge === 'Unknown') {
                return collect([
                    'status' => 'Missing',
                    'message' => 'Next stage, is missing person in charge then approval process will on hold after this stage',
                    'waiting' => $approvals->user->name,
                    'on_stage' => $approvals->approvalFlow->level,
                    'next_level' => $next->level,
                    'next_stage_id' => $next->id,
                    'next_person_charge' => 0,
                    'next_person' => 'Missing',
                ]);
                return abort(500, 'person in charge is missing and its required because this stage is not skipable');
            }
            if ($next->level === 'Purchasing') {
                return collect([
                    'status' => 'Completed',
                    'message' => 'Approval process is completed',
                    'waiting' => 'Completed',
                    'on_stage' => 'Completed',
                    'next_stage' => 'Completed',
                    'next_stage_id' => 'Completed',
                    'next_person_charge' => 'Completed',
                    'next_person' => 'Completed',
                ]);
            }
            return collect([
                'status' => $approvals->status,
                'message' => 'Still waiting for action on this stage, if approved then approval process going to next stage',
                'waiting' => $approvals->user->name,
                'on_stage' => $approvals->approvalFlow->level,
                'next_stage' => $next->level,
                'next_stage_id' => $next->id,
                'next_person_charge' => $incharge->id,
                'next_person' => $incharge->name,
            ]);
        }

        return collect([
            'status' => $approvals->status,
            'message' => 'Still waiting for action on this stage, if approved then approval process is complete',
            'waiting' => $approvals->user->name,
            'on_stage' => $approvals->approvalFlow->level,
            'next_stage' => 'Completed',
            'next_stage_id' => 'Completed',
            'next_person_charge' => 'Completed',
            'next_person' => 'Completed',
        ]);
    }
}
