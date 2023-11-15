@extends('emails.purchase.layouts.mail')

@section('title')
Request Approved
@endsection

@section('date_created')
{{\Carbon\Carbon::parse($approvalRequest->created_at)->toDayDateTimeString()}}
@endsection

@section('first_paragraph')
We are pleased to inform you that your recent request form has been approved! <span style="font-weight: bold;">Your {{$approvalRequest->approvalFlow->level}}</span> has reviewed your request. <br><br> Here are the details of your request:
@endsection

@section('second_paragraph')
<span style="font-weight:bold;">Request Number : </span>{{$approvalRequest->approvable->request_number}}<br>
<span style="font-weight:bold;">Category : </span>{{$approvalRequest->approvable->category->name}}<br>
<span style="font-weight:bold;">Items Count : </span>{{$approvalRequest->approvable->requestItems->count()}}<br>
<span style="font-weight:bold;">Notes : </span>{{$approvalRequest->approvable->note}}<br>

@endsection

@section('third_paragraph')
@if ($approvalRequest->approvalFlow->is_last_stage !== true)
Your request is now moving forward to the next level of approval. The system continue the process to ensure all necessary steps are taken.
<br><br>
@endif
If there are updates regarding your request, we will keep you informed through email or you can check with below link.<br>
<br><a style="font-weight:bold;" href="{{'https://teresa.hoplun.com/purchase/'.$user->company->short_name.'/requests?activeTab=submited'}}">My Request</a>
@endsection

@section('signature')
Best Regards,<br>
Teresa Purchase Helpdesk
@endsection
