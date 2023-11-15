<x-filament-panels::page data-theme="teresa">



    {{-- Notice `expandable` and `wire:model` --}}
    <x-table :headers="$headers" :rows="$records" wire:model="expanded" expandable>

        {{-- Special `expansion` slot --}}
        @scope('expansion', $record)
        <div class="bg-base-200 p-8 font-bold">
            Hello, {{ $record->status }}!
        </div>
        @endscope

    </x-table>






</x-filament-panels::page>
