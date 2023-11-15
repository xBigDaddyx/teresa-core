@extends('emails.kanban.layouts.mail2')

@section('title')
Action Required
@endsection

@section('date_created')
Missing next plan queue
@endsection

@section('first_paragraph')
We hope this message finds you well. We have a plan queue that requires your attention and action. A plan queue doesn't have next queue, and it's needed to proceed further.
<br><br>Here are the details of the plan queue:
@endsection

@section('second_paragraph')
<span style="font-weight:bold;">Sewing : </span>{{$planQueue->plan->sewing_id}}<br>
<span style="font-weight:bold;">Contract : </span>{{$planQueue->plan->contract_id}}<br>
<span style="font-weight:bold;">Style : </span>{{$planQueue->plan->style_id}}<br>
<span style="font-weight:bold;">Sewing Start : </span>{{$planQueue->plan->sewing_start_date}}<br>
<span style="font-weight:bold;">Sewing End : </span>{{$planQueue->plan->sewing_end_date}}<br>
<span style="font-weight:bold;">Status : </span>{{$planQueue->status}}<br>

@endsection

@section('third_paragraph')
<span style="font-weight:bold;">Please check</span> if the plan queue is over, then will stay as delayed status. You can check by this link<br>
<br><a style="font-weight:bold;" href="{{'https://teresa.hoplun.com/kanban/'.$user->company->short_name.'/plan-queues'}}">Plan Queues</a>
@endsection

@section('signature')
Best Regards,<br>
Teresa Purchase Helpdesk
@endsection
