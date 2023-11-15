<?php

namespace App\Listeners;

use App\Events\RequestApproved;
use App\Mail\SendRequestApprovedNotification;
use Domain\Purchases\Models\ApprovalFlow;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Users\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class RequestApprovedListener
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
    public function handle(RequestApproved $event): void
    {

        $approvalRequest = ApprovalRequest::find($event->request->id);
        $request = $approvalRequest->approvable;
        $next_approval = $approvalRequest->approvable->getNextApproval();
        $new = new ApprovalRequest();
        if ($next_approval['next_stage'] !== 'Completed') {

            $new->status = 'Approved';
            $new->approval_flow_id = $next_approval['next_stage_id'];
            $new->approvable_id = $approvalRequest->approvable->id;
            $new->approvable_type = $approvalRequest->approvable->getNamespace();
            $new->last_status = $approvalRequest->status;
            $new->user_id = $next_approval['next_person_charge'];
            $new->action = 'Approve';
            $new->company_id = $approvalRequest->company_id;
            $new->created_by = $next_approval['next_person_charge'];
            $new->save();
        } else {
            $new->status = 'Completed';
            $new->approval_flow_id = $approvalRequest->approval_flow_id;
            $new->approvable_id = $approvalRequest->approvable->id;
            $new->approvable_type = $approvalRequest->approvable->getNamespace();
            $new->last_status = $approvalRequest->status;
            $new->user_id = $approvalRequest->user_id;
            $new->action = 'Approve';
            $new->company_id = $approvalRequest->company_id;

            $new->save();
        }


        $approvalRequest->delete();


        Mail::to($event->request->createdBy->email)->send(
            new SendRequestApprovedNotification(User::find($request->created_by), $new)
        );
    }
}
