<div>
    {{$this->table}}
    <form wire:submit="create" class="py-4">
        {{ $this->form }}
        <div class="py-4">
            <x-filament::button type="submit" size="sm" icon="tabler-plus" tooltip="Add a comment">
                Submit
            </x-filament::button>
        </div>

    </form>
</div>
