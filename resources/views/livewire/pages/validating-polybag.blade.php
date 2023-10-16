<div class="max-w-7xl mx-auto">
    <livewire:components.polybag-stats :$carton :$type :$polybags />
    <div class="max-w-xl mx-auto card lg:card-side bg-base-100 shadow-md max-h-xl mb-4 mt-4">
        <figure><img src="{{ asset('storage/images/carton-box-illu02.jpg') }}" style="height:260px;"></figure>
        <div class="card-body">

            <x-form wire:submit.prevent="validation">
                <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag barcode" wire:model.defer="polybagForm.polybag_code" hint="Please scan polybag barcode here." />
                <x-slot:actions>

                    <x-button label="Show Table" icon="m-window" class="btn-info" spinner="save" wire:click="toggleShowTable" />
                    <x-button label="Reset" icon="m-arrow-path" class="btn-primary" spinner="save" :link="route('accuracy.check.carton')" />
                </x-slot:actions>
            </x-form>

        </div>
    </div>
    @if($showTable)
    <div wire:transition>
        <livewire:components.polybag-table :$carton />
    </div>
    @endif
</div>