<div class="max-w-full px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto" wire:poll.60s.keep-alive>
    <!-- max-w-[85rem -->

    <!-- Grid -->

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 2xl:grid-cols-12 gap-4">


        @foreach ($wises as $wise)

        <div class="card 2xl:card-side w-30 bg-base-100 shadow-md">
            @if ($wise->status === 'Standard')
            <figure class="lg:hidden p-4 bg-success font-bold">
                {{$wise->sewing_line_type}}
            </figure>
            @elseif($wise->status === 'Timeout')
            <figure class="lg:hidden p-4 bg-error font-bold animate-pulse animate-infinite text-white">
                {{$wise->sewing_line_type}}
            </figure>
            @elseif($wise->status === 'Zero')
            <figure class="lg:hidden p-4 bg-error font-bold animate-pulse animate-infinite text-white">
                {{$wise->sewing_line_type}}
            </figure>
            @elseif($wise->status === 'Low')
            <figure class="lg:hidden p-4 bg-warning font-bold animate-flash animate-infinite text-white">
                {{$wise->sewing_line_type}}
            </figure>
            @else
            <figure class="lg:hidden p-4 bg-neutral font-bold text-white">
                {{$wise->sewing_line_type}}
            </figure>
            @endif
            <div class="card-body items-center text-center">
                <h2 class="card-title">{{$wise->sewing_display_name}}</h2>
                <p class="sm:text-sm lg:hidden">{{$wise->plan->style_id}} ({{$wise->plan->contract_id}})</p>

            </div>
        </div>


        @endforeach



    </div>
    @if($hasMorePages)
    <div x-data x-intersect="@this.call('loadWises')" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-8 2xl:grid-cols-12 gap-4">
        @foreach(range(1, 4) as $x)
        @include('livewire.pages.skeleton')
        @endforeach
    </div>
    @endif

    <!-- End Grid -->
</div>
