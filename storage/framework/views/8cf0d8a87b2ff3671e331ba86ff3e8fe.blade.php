<div>
    <!-- STANDARD LABEL -->
    @if($label && !$inline)
        <label class="pt-0 label label-text font-semibold">{{ $label }}</label>
    @endif

    <!-- PREFIX/SUFFIX/PREPEND/APPEND CONTAINER -->
    @if($prefix || $suffix || $prepend || $append)
        <div class="flex">
    @endif

    <!-- PREFIX / PREPEND -->
    @if($prefix || $prepend)
        <div class="rounded-l-lg flex items-center bg-base-200 @if($prefix) border border-base-300 px-4 @endif">
            {{ $prepend ?? $prefix }}
        </div>
    @endif

    <div class="flex-1 relative">
        <!-- MONEY SETUP -->
        @if($money)
            <div
                wire:key="money-{{ rand() }}"
                x-data="{ amount: $wire.{{ $modelName() }} }" x-init="$nextTick(() => new Currency($refs.myInput, {{ $moneySettings() }}))"
            >
        @endif

        <!-- INPUT -->
        <input
            id="{{ $uuid }}"
            placeholder = "{{ $attributes->whereStartsWith('placeholder')->first() }} "
            x-ref="myInput"

            @if($money)
                :value="amount"
                @input="$nextTick(() => $wire.{{ $modelName() }} = Currency.getUnmasked())"
            @endif

            {{
                $attributes
                    ->merge(['type' => 'text'])
                    ->except($money ? 'wire:model' : '')
                    ->class([
                        'input input-primary w-full peer',
                        'pl-10' => ($icon),
                        'h-14' => ($inline),
                        'pt-3' => ($inline && $label),
                        'rounded-l-none' => $prefix || $prepend,
                        'rounded-r-none' => $suffix || $append,
                        'border border-dashed' => $attributes->has('readonly'),
                        'input-error' => $errors->has($modelName())
                ])
            }}
        />

        <!-- ICON  -->
        @if($icon)
            <x-icon :name="$icon" class="absolute top-1/2 -translate-y-1/2 left-3 text-gray-400 " />
        @endif

        <!-- RIGHT ICON  -->
        @if($iconRight)
            <x-icon :name="$iconRight" class="absolute top-1/2 right-3 -translate-y-1/2 text-gray-400 " />
        @endif

        <!-- INLINE LABEL -->
        @if($label && $inline)
            <label for="{{ $uuid }}" class="absolute text-gray-400 duration-300 transform -translate-y-1 scale-75 top-2 origin-[0] bg-white rounded dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-1 @if($inline && $icon) left-9 @else left-3 @endif">
                {{ $label }}
            </label>
        @endif

        <!-- HIDDEN MONEY INPUT + END MONEY SETUP -->
        @if($money)
                <input type="hidden" {{ $attributes->only('wire:model') }} />
            </div>
        @endif
    </div>

    <!-- SUFFIX/APPEND -->
    @if($suffix || $append)
        <div class="rounded-r-lg flex items-center bg-base-200 @if($suffix) border border-base-300 px-4 @endif">
            {{ $append ?? $suffix }}
        </div>
    @endif

    <!-- END: PREFIX/SUFFIX/APPEND/PREPEND CONTAINER  -->
    @if($prefix || $suffix || $prepend || $append)
        </div>
    @endif

    <!-- ERROR -->
    @error($modelName())
        <div class="text-red-500 label-text-alt p-1">{{ $message }}</div>
    @enderror

    <!-- HINT -->
    @if($hint)
        <div class="label-text-alt text-gray-400 p-1 pb-0">{{ $hint }}</div>
    @endif
</div>