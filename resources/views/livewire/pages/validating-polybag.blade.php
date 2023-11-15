<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto " wire.poll>
    <div class="grid md:grid-cols-6 gap-4  mb-4 ">
        <div class="alert alert-warning shadow-sm col-span-5">
            <x-heroicon-o-exclamation-triangle class="stroke-current shrink-0 h-6 w-6" />
            <div>
                <h3 class="font-bold">This carton box is {{session()->get('carton.type')}} </h3>
                @if (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX')
                <div class="text-xs">After finish validating garment tag, close by scanning polybag barcode or carton box barcode.</div>
                @elseif(session()->get('carton.type') === 'SOLID')
                <div class="text-xs">Validating each polybags inside the carton box.</div>
                @endif

            </div>

        </div>
        <div class="alert alert-error text-white shadow-sm justify-self-end">
            <x-heroicon-o-clock class="stroke-current shrink-0 h-6 w-6" />
            <div>
                <h3 class="font-bold">Clock</h3>
                <span id="clock" onload="currentTime()"></span>
            </div>

        </div>
    </div>
    @if (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX')
    <div class="max-w-screen mb-8">
        <livewire:components.polybag-stats :$carton :$type :$polybags :$tags />
    </div>

    @endif
    <div class="grid md:grid-cols-6 gap-4">

        <div class="max-w-4xl mx-auto card lg:card-side shadow-md max-h-80 mb-4 mt-4 col-span-4 bg-base-100">
            @if (session()->get('carton.type') === 'SOLID')
            <figure><img src="{{ asset('storage/images/carton-box-illu02.jpg') }}" style="height:400px;" class="hidden md:block"></figure>
            @elseif (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX')
            <figure><img src="{{ asset('storage/images/carton-box-illu06.jpg') }}" style="height:500px;" class="hidden md:block"></figure>
            @endif

            <div class="card-body">

                <x-form wire:submit="validation">
                    @if (session()->get('carton.type') === 'SOLID')
                    @if ($completed)
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag barcode" wire:model="form.polybag_barcode" hint="Please scan polybag barcode here." disabled />
                    @else
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag barcode" wire:model="form.polybag_barcode" hint="Please scan polybag barcode here." />
                    @endif
                    @elseif (session()->get('carton.type') === 'RATIO' ||session()->get('carton.type') === 'MIX')
                    @if ($completed)
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Garment tag barcode" wire:model="form.tag_barcode" hint="Please scan garment tag barcode here." disabled />
                    @elseif ($polybagCompleted)
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Polybag/Carton barcode" wire:model="form.polybag_barcode" hint="Please scan polybag/carton barcode here." />
                    @else
                    <x-input class="input input-bordered input-primary focus:ring-primary focus:outline-primary" autofocus label="Garment tag barcode" wire:model="form.tag_barcode" hint="Please scan garment tag barcode here." />
                    @endif
                    @endif


                    <x-slot:actions>

                        <x-button label="Show Table" icon="m-window" class="btn-info" spinner="save" wire:click="toggleShowTable" />
                        <x-button label="Reset" icon="m-arrow-path" class="btn-primary" spinner="save" :link="route('accuracy.check.carton')" />
                    </x-slot:actions>
                </x-form>

            </div>

        </div>
        @if (session()->get('carton.type') === 'RATIO' || session()->get('carton.type') === 'MIX')
        <div class="col-span-2">
            <livewire:components.polybag-attributes :$carton :$type :$polybags />

        </div>

        @endif
        @if (session()->get('carton.type') === 'SOLID')
        <livewire:components.polybag-stats :$carton :$type :$polybags />
        @endif
    </div>

    @if ($showTable)
    <div wire:transition class="max-w-7xl mt-8">

        <livewire:kanban.validation-table :$carton />

    </div>
    @endif
</div>
<script>
    function currentTime() {
        let date = new Date();
        let hh = date.getHours();
        let mm = date.getMinutes();
        let ss = date.getSeconds();
        let session = "AM";

        if (hh === 0) {
            hh = 12;
        }
        if (hh > 12) {
            hh = hh - 12;
            session = "PM";
        }

        hh = (hh < 10) ? "0" + hh : hh;
        mm = (mm < 10) ? "0" + mm : mm;
        ss = (ss < 10) ? "0" + ss : ss;

        let time = hh + ":" + mm + ":" + ss + " " + session;

        document.getElementById("clock").innerText = time;
        let t = setTimeout(function() {
            currentTime()
        }, 1000);
    }

    currentTime();
</script>
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
