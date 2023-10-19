<div class="max-w-7xl px-4 py-2 sm:px-6 lg:px-8 lg:py-4 mx-auto max-h-screen">

    <div class="max-w-2xl mx-auto card lg:card-side bg-base-100 shadow-xl">

        <figure><img src="{{ asset('storage/images/carton-box-illu04.jpg') }}" style="height: 460px;" class="hidden md:block"></figure>
        <div class="card-body">
            <h2 class="card-title">Carton Box Check</h2>
            <p>Check the availability of carton boxes.</p>
            <x-form d="boxCheck" wire:submit="check">
                <x-input label="Carton box barcode" placeholder="" icon="o-qr-code" hint="Check carton box is available or not." wire:model.live="boxForm.box_code" autofocus autocomplete="off" />
                @if($showExtraForm === true)
                <x-select label="PO" option-value="po" option-label="po" icon="o-document-text" :options="$pos" wire:model.live="extraForm.selectedPo" inline />
                @if ($extraForm['selectedPo'] !== '-- Select PO --')
                <x-select label="Carton Number" option-value="carton_number" option-label="carton_number" icon="o-pencil-square" :options="$carton_numbers" wire:model.live="extraForm.selectedCartonNumber" inline />
                @endif
                @endif

                <x-slot:actions>
                    <x-button label="Check" class="btn-primary" icon="o-magnifying-glass-circle" type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>

        </div>
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
            })
        })
    })
</script>
