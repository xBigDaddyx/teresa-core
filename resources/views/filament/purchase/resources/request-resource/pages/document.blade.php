<x-filament-panels::page>
    @if ($record->isSubmitted())
    <div role="dy-alert" class="dy-alert dy-alert-warning">
        <x-tabler-lock />
        <span>Locked, because this request have been submitted</span>
    </div>

    @endif

    {{ $this->requestInfolist }}


    {{$this->table}}
</x-filament-panels::page>
