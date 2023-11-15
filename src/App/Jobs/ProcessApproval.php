<?php

namespace App\Jobs;

use App\Events\OrderApproved;
use App\Events\OrderRejected;
use App\Events\OrderSubmited;
use App\Events\RequestApproved;
use App\Events\RequestRejected;
use App\Events\RequestSubmited;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\Order;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public Model $request;
    public string $action;
    public User $user;
    public string $type;
    public ?ApprovalRequest $approvalRequest;
    /**
     * Create a new job instance.
     */
    public function __construct(Model $request, ApprovalRequest $approvalRequest = null, $action, $user)
    {
        $this->request = $request;
        $this->action = $action;
        $this->user = $user;
        $this->approvalRequest = $approvalRequest;
        if (str_contains($this->request->table, 'requests')) {
            $this->type = 'PR';
        } else if (str_contains($this->request->table, 'orders')) {
            $this->type = 'PO';
        }
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info($this->request->table);
        $this->initApproval();
    }
    public function initApproval()
    {

        if ($this->request->getApprovalRecords()->count() <= 0) {
            $flows = $this->request->getApprovalFlow();
            $first_flow = $flows->where('type', $this->type)->where('order', 1)->first();
            $department = $this->request->department;
            $person_in_charge = User::whereHas('purchaseDepartments', function (Builder $query) use ($department, $first_flow) {
                $query->where('department_id', $department->id)->where('role', $first_flow->level)->whereBelongsTo(Filament::getTenant());
            })->first();
            Log::info($person_in_charge);
            $approvalRequest = new ApprovalRequest();
            $approvalRequest->status = 'Submited';
            $approvalRequest->approvable_id = $this->request->id;
            $approvalRequest->approvable_type = $this->request->getNamespace();
            $approvalRequest->last_status = null;
            $approvalRequest->user_id = $person_in_charge->id;
            $approvalRequest->action = 'Submit';
            $approvalRequest->company_id = $this->user->company->id;
            $approvalRequest->created_by = $this->user->id;
            $approvalRequest->save();

            if ($this->type === 'PR') {
                return RequestSubmited::dispatch($approvalRequest);
            }
            return OrderSubmited::dispatch($approvalRequest);
        }
        switch ($this->action) {

            case 'approve':
                if ($this->type === 'PR') {
                    return RequestApproved::dispatch($this->approvalRequest);
                }
                return OrderApproved::dispatch($this->approvalRequest);

                // Code to handle the 'edit' action
                //return view('edit');
                break;

            default:
                if ($this->type === 'PR') {
                    return RequestRejected::dispatch($this->approvalRequest);
                }
                return OrderRejected::dispatch($this->approvalRequest);
                // Code to handle other actions or a reject case
                //return view('default');
                break;
        }
    }
    // public function approve()
    // {
    //     //
    // }
    // public function reject()
    // {
    //     //
    // }
    // public function submit()
    // {
    //     if ($this->request->is_submited === false) {

    //         $this->request->setConnection('teresa_purchase');
    //         $query = $this->request->setKeysForSaveQuery($this->request->newModelQuery());
    //         $columns = [$this->request->getSubmittedColumn() => true];
    //         $query->update($columns);
    //         $this->request->syncOriginalAttributes(array_keys($columns));

    //         if (str_contains($this->request->table, 'PR') || str_contains($this->request->table, 'requests')) {
    //             $type = 'PR';
    //         } else if (str_contains($this->request->table, 'PO') || str_contains($this->request->table, 'orders')) {
    //             $type = 'PO';
    //         }
    //         $flows = $this->getApprovalFlow();
    //         $first_flow = $flows->where('type', $type)->where('order', 1)->first();
    //         $department = $this->department;
    //         $person_in_charge = User::whereHas('purchaseDepartments', function (Builder $query) use ($department, $first_flow) {
    //             $query->where('department_id', $department->id)->where('role', $first_flow->level)->whereBelongsTo(Filament::getTenant());
    //         })->first();
    //         return Approva::create([
    //             'status' => 'Submitted',
    //             'approval_flow_id' => $first_flow->id,
    //             'approvable_id' => $this->id,
    //             'approvable_type' => 'Domain\Purchases\Models\Request',
    //             'last_status' => null,
    //             'user_id' => $person_in_charge->id,
    //             'action' => 'Submit',
    //             'company_id' => auth()->user()->company->id,
    //             'created_by' => auth()->user()->id,
    //         ]);
    //     }
    // }
}
