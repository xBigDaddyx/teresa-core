@extends('emails.purchase.layouts.mail')

@section('title')
Request Approval
@endsection

@section('date_created')
{{\Carbon\Carbon::parse($approvalRequest->created_at)->toDayDateTimeString()}}
@endsection

@section('first_paragraph')
We hope this message finds you well. We have a request that requires your attention and approval. A request form has been submitted, and your approval is needed to proceed further.
<br><br>Here are the details of the request:
@endsection

@section('second_paragraph')
<span>Request Number : </span>{{$approvalRequest->approvable->request_number}}<br>
<span>Category : </span>{{$approvalRequest->approvable->category->name}}<br>
<span>Items Count : </span>{{$approvalRequest->approvable->requestItems->count()}}<br>
<span>Notes : </span>{{$approvalRequest->approvable->note}}<br>

@endsection

@section('third_paragraph')
Your input and approval are crucial to move forward with this request. We kindly request that you review the details and provide your approval at your earliest convenience.<br></br>

To approve the request, please follow these steps:<br>

<span>Click on the following link to access the request form: <a style="font-weight:bold;" href="{{'https://teresa.hoplun.com/purchase/'.$user->company.'/approval-request'}}">Approval Request</a></span>

@endsection

@section('signature')
Best Regards,<br>
Teresa Purchase Helpdesk
@endsection
