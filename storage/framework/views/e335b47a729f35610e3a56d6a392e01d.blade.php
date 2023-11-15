    <div wire:key="{{ $uuid }}">                
        <!-- STANDARD LABEL -->
        @if($label && !$inline)
            <label class="pt-0 label label-text font-semibold">{{ $label }}</label>
        @endif
        
        <div class="relative">                 
            <select 
                {{ $attributes->whereDoesntStartWith('class') }} 
                {{ $attributes->class([
                            'select select-primary w-full font-normal', 
                            'pl-10' => ($icon), 
                            'h-14' => ($inline),
                            'pt-3' => ($inline && $label),
                            'border border-dashed' => $attributes->has('readonly'),
                            'select-error' => $errors->has($modelName())
                        ]) 
                }}
                
            >
                @if($placeholder)
                    <option>{{ $placeholder }}</option>
                @endif

                @foreach ($options as $option)
                    <option value="{{ $option[$optionValue] }}" @if(isset($option['disabled'])) disabled @endif>{{ $option[$optionLabel] }}</option>
                @endforeach
            </select>

            <!-- ICON -->
            @if($icon)
                <x-icon :name="$icon" class="absolute top-1/2 -translate-y-1/2 left-3 text-gray-400" />                     
            @endif

            <!-- RIGHT ICON  -->
            @if($iconRight)
                <x-icon :name="$iconRight" class="absolute top-1/2 right-8 -translate-y-1/2 text-gray-400 " />
            @endif

            <!-- INLINE LABEL -->
            @if($label && $inline)                        
                <label class="absolute text-gray-500 duration-300 transform -translate-y-1 scale-75 top-2 origin-[0] bg-white rounded dark:bg-gray-900 px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-1 @if($inline && $icon) left-9 @else left-3 @endif">
                    {{ $label }}                                
                </label>                                                 
            @endif
        </div>

         <!-- ERROR -->
         @error($modelName())
            <div class="text-red-500 label-text-alt p-1">{{ $message }}</div>
        @enderror

        <!-- HINT -->
        @if($hint)
            <div class="label-text-alt text-gray-400 pl-1 mt-2">{{ $hint }}</div>
        @endif
    </div>