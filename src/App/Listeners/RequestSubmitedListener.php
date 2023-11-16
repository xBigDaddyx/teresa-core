<?php

namespace App\Listeners;

use App\Events\RequestSubmited;
use App\Mail\SendRequestSubmitedNotification;
use Domain\Purchases\Models\ApprovalHistory;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\Request;
use Domain\Users\Models\User;
use Filament\Facades\Filament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestSubmitedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RequestSubmited $event): void
    {
        $flows = $event->request->getApprovalFlow();
        $first_flow = $flows->where('type', $event->type)->where('order', 0)->first();
        $second_flow = $flows->where('type', $event->type)->where('order', 1)->first();
        $department = $event->request->department;
        $person_in_charge = User::whereHas('approvalUser', function (Builder $query) use ($department, $second_flow) {
            $query->where('department_id', $department->id)->where('level', $second_flow->level)->whereBelongsTo(Filament::getTenant());
        })->first();
        $approvalRequest = new ApprovalRequest();
        $approvalRequest->status = 'Submited';
        $approvalRequest->approval_flow_id = $first_flow->id;
        $approvalRequest->approvable_id = $event->request->id;
        $approvalRequest->approvable_type = $event->request->getNamespace();
        $approvalRequest->last_status = null;
        $approvalRequest->user_id = $person_in_charge->id;
        $approvalRequest->action = 'Submit';
        $approvalRequest->company_id = $event->user->company->id;
        $approvalRequest->created_by = $event->user->id;
        $approvalRequest->save();

        $history = new ApprovalHistory();
        $history->approvable_id = $event->request->id;
        $history->approvable_type = $event->request->getNamespace();
        $history->action = 'Submit';
        $history->user_id = $event->user->id;
        $history->save();

        $model = Request::find($event->request->id);
        $model->is_submited = true;
        $model->save();

        //notify user
        Mail::to($event->request->createdBy->email)->send(
            new SendRequestSubmitedNotification(User::find($event->request->created_by), $event->request->getApprovalRecords())
        );
    }
}
