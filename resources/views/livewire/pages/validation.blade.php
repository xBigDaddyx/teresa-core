<div class="max-w-7xl px-4 py-2 sm:px-6 lg:px-8 lg:py-4 mx-auto max-h-screen">

    <div class="max-w-2xl mx-auto card lg:card-side bg-base-100 shadow-xl">

        <figure><img src="{{ asset('storage/images/carton-box-illu01.jpg') }}" class="h-96"></figure>
        <div class="card-body">
            <h2 class="card-title">Carton Box Check</h2>
            <p>Check the availability of carton boxes.</p>
            <x-form d="boxCheck" wire:submit="check">

                <x-input label="Carton box barcode" placeholder="" icon="o-qr-code" hint="Check carton box is available or not." wire:model.defer="boxForm.box_code" autofocus autocomplete="off" />
                <x-slot:actions>
                    <x-button label="Check" class="btn-primary" icon="o-magnifying-glass-circle" type="submit" spinner="save" />
                </x-slot:actions>
            </x-form>

        </div>
    </div>
    <!-- Modal Alert -->
    <x-modal wire:model="alert" title="Error" subtitle="Something wrong.">
        <div class="card card-side">
            <x-heroicon-o-x-circle class="text-danger-500 w-20 h-20" />
            <div class="text-left p-2 ml-2 ma-w-xl">
                <h2 class="text-2xl font-bold text-danger-500">Carton box not found!</h2>
                <p>Please check to your admin for available this carton.</p>
            </div>

        </div>


        <x-slot:actions>
            <x-button label="Confirm" class="btn-primary" @click="$wire.alert = false" />
        </x-slot:actions>
    </x-modal>
    <!-- End Modal Alert -->
</div>