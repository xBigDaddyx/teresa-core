    @if($link)
        <a href="{{ $link }}"
    @else
        <button
    @endif

        wire:key="{{ $uuid }}"
        {{ $attributes->whereDoesntStartWith('class') }}
        {{ $attributes->class(['btn normal-case', "tooltip $tooltipPosition" => $tooltip]) }}
        {{ $attributes->merge(['type' => 'button']) }}

        @if($link && $external)
            target="_blank"
        @endif

        @if($link && ! $external)
            wire:navigate
        @endif

        @if($tooltip)
            data-tip="{{ $tooltip }}"
        @endif

        @if($spinner)
            wire:target="{{ $spinnerTarget() }}"
            wire:loading.attr="disabled"
        @endif
    >

        <!-- SPINNER -->
        @if($spinner)
            <span wire:loading wire:target="{{ $spinnerTarget() }}" class="loading loading-spinner w-5 h-5"></span>
        @endif

        <!-- ICON -->
        @if($icon)
            <span @if($spinner) wire:loading.remove wire:target="{{ $spinnerTarget() }}" @endif>
                <x-icon :name="$icon" />
            </span>
        @endif

        {{ $label ?? $slot }}

        <!-- ICON RIGHT -->
        @if($iconRight)
            <span @if($spinner) wire:loading.remove wire:target="{{ $spinnerTarget() }}" @endif>
                <x-icon :name="$iconRight" />
            </span>
        @endif

    @if(!$link)
        </button>
    @else
        </a>
    @endif

    <!--  Force tailwind compile tooltip classes   -->
    <span class="hidden tooltip tooltip-left tooltip-right tooltip-bottom tooltip-top"></span>