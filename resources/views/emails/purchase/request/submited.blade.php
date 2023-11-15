@extends('emails.purchase.layouts.mail')

@section('title')
Request Submitted
@endsection

@section('date_created')
{{\Carbon\Carbon::parse($approvalRequest->created_at)->toDayDateTimeString()}}
@endsection

@section('first_paragraph')
I hope this message finds you well. We wanted to inform you that your recent request form has been successfully submitted. the system is already working on processing your request. <br><br> Here are the details of your request:
@endsection

@section('second_paragraph')
<span style="font-weight:bold;">Request Number : </span>{{$approvalRequest->approvable->request_number}}<br>
<span style="font-weight:bold;">Category : </span>{{$approvalRequest->approvable->category->name}}<br>
<span style="font-weight:bold;">Items Count : </span>{{$approvalRequest->approvable->requestItems->count()}}<br>
<span style="font-weight:bold;">Notes : </span>{{$approvalRequest->approvable->note}}<br>

@endsection

@section('third_paragraph')
<span style="font-weight:bold;">Your Department Supervisor</span> will now review your request and take the necessary steps to fulfill it in a timely manner. If there are updates regarding your request, we will keep you informed through email or you can check with below link.<br>
<br><a style="font-weight:bold;" href="{{'https://teresa.hoplun.com/purchase/'.$user->company->short_name.'/requests?activeTab=submited'}}">My Request</a>
@endsection

@section('signature')
Best Regards,<br>
Teresa Purchase Helpdesk
@endsection
