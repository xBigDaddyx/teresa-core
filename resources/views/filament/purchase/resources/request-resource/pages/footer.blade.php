<div class="grid grid-cols-4 gap-4">
    <x-filament::section>
        <x-slot name="heading">
            User
        </x-slot>

        <x-slot name="description">

        </x-slot>
        @if ($record->isSubmitted())
        <div class="flex items-center justify-center">
            <img src="{{$record->createdBy->getSignature()}}" alt="Signature" />
        </div>


        <div class="flex items-center gap-3">
            <div class="avatar">
                <div class="mask mask-squircle w-12 h-12">
                    <img src="{{$record->createdBy->avatar}}" alt="Avatar" />
                </div>
            </div>
            <div>
                <div class="font-bold"> {{$record->createdBy->name}}</div>
                <div class="text-sm opacity-50">{{\Carbon\Carbon::parse($record->created_at)->format("d M Y H:i:s")}}</div>
            </div>
        </div>
        @endif
    </x-filament::section>

    @foreach($flows as $flow)
    @php
    $approver = $approvals->where('process_approval_flow_step_id',$flow->id)->where('approval_action','Approved')->first();

    @endphp
    <x-filament::section>
        <x-slot name="heading">
            {{ucfirst(str_replace('department-', '', $flow->role->name))}}
        </x-slot>

        <x-slot name="description">

        </x-slot>
        @if (!empty($approver->user))
        <div class="flex items-center justify-center">
            <img src="{{$approver->user->getSignature()}}" alt="Avatar" />
        </div>
        <div class="flex items-center gap-3">
            <div class="avatar">
                <div class="mask mask-squircle w-12 h-12">
                    <img src="{{$approver->user->avatar}}" alt="Avatar" />
                </div>
            </div>
            <div>
                <div class="font-bold"> {{$approver->user->name}}</div>
                <div class="text-sm opacity-50">{{\Carbon\Carbon::parse($approver->created_at)->format("d M Y H:i:s")}}</div>
            </div>
        </div>
        @endif
    </x-filament::section>

    @endforeach

</div>
