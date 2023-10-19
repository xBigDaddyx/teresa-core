<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto" wire.poll>
    <div class="grid md:grid-cols-2 gap-4">

        <div class="max-w-xl mx-auto card lg:card-side bg-base-100 shadow-md max-h-xl mb-4 mt-4">
            @if ($carton->type === 'SOLID')
            <figure><img src="{{ asset('storage/images/carton-box-illu02.jpg') }}" style="height:400px;" class="hidden md:block"></figure>
            @elseif ($carton->type === 'RATIO')
            <figure><img src="{{ asset('storage/images/carton-box-illu06.jpg') }}" style="height:400px;" class="hidden md:block"></figure>
            @endif

            <div class="card-body">

                <x-form wire:submit.prevent="validation">
                    @if ($carton->type === 'SOLID')
                    @if ($completed)
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag barcode" wire:model.defer="polybagForm.polybag_code" hint="Please scan polybag barcode here." disabled />
                    @else
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag barcode" wire:model.defer="polybagForm.polybag_code" hint="Please scan polybag barcode here." />
                    @endif
                    @elseif ($carton->type === 'RATIO')
                    @if ($completed)
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Garment tag barcode" wire:model.defer="tagForm.tag_code" hint="Please scan garment tag barcode here." disabled />
                    @elseif ($polybagCompleted)
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag/Carton barcode" wire:model.defer="polybagForm.polybag_code" hint="Please scan polybag/carton barcode here." />
                    @else
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Garment tag barcode" wire:model.defer="tagForm.tag_code" hint="Please scan garment tag barcode here." />
                    @endif
                    @endif


                    <x-slot:actions>

                        <x-button label="Show Table" icon="m-window" class="btn-info" spinner="save" wire:click="toggleShowTable" />
                        <x-button label="Reset" icon="m-arrow-path" class="btn-primary" spinner="save" :link="route('accuracy.check.carton')" />
                    </x-slot:actions>
                </x-form>

            </div>
        </div>
        <livewire:components.polybag-stats :$carton :$type :$polybags />
        @if ($showTable)
        <div wire:transition>
            <livewire:components.polybag-table :$carton />
        </div>
        @endif
    </div>

</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        @this.on('swal', (event) => {
            const data = event
            swal.fire({
                icon: data[0]['icon'],
                title: data[0]['title'],
                text: data[0]['text'],
                showConfirmButton: data[0]['showConfirmButton'],
                allowOutsideClick: data[0]['allowOutsideClick'],
            })
        })
    })
</script>
