<div class="px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto" wire:poll.keep-alive>
    <!-- Grid -->
    <div class="grid sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-7 gap-3 sm:gap-6">
        @foreach ($wises as $wise)
        <!-- Card -->
        @if ($wise->status === 'Standard')
        <a class="group flex flex-col bg-success border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
            <div class="p-4 md:p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl group-hover:text-primary font-bold text-white dark:group-hover:text-gray-400 dark:text-gray-200">
                            {{$wise->sewing_display_name}}
                        </h3>
                        <h3 class="text-xl font-bold text-primary">
                            {{$wise->sewing_line_type}}
                        </h3>
                        <p class="text-sm text-white">
                            Style : {{$wise->plan->style_id}} <br> Contract : ({{$wise->plan->contract_id}})
                        </p>
                    </div>
                    <div class="ps-3">
                        <x-tabler-thumb-up-filled class="flex-shrink-0 w-8 h-8 text-neutral" />

                    </div>
                </div>
            </div>
        </a>
        @elseif($wise->status === 'Timeout')
        <a class="group flex flex-col bg-error animate-pulse animate-infinite text-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
            <div class="p-4 md:p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl group-hover:text-primary font-bold text-white dark:group-hover:text-gray-400 dark:text-gray-200">
                            {{$wise->sewing_display_name}}
                        </h3>
                        <h3 class="text-xl font-bold text-primary">
                            {{$wise->sewing_line_type}}
                        </h3>
                        <p class="text-sm font-bold">
                            Style : {{$wise->plan->style_id}} <br> Contract : ({{$wise->plan->contract_id}})
                        </p>
                    </div>
                    <div class="ps-3">
                        <x-tabler-shopping-cart-down class="flex-shrink-0 w-8 h-8 text-white" />

                    </div>
                </div>
            </div>
        </a>

        @elseif($wise->status === 'Zero')
        <a class="group flex flex-col bg-error animate-pulse animate-infinite text-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
            <div class="p-4 md:p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl group-hover:text-primary font-bold text-white dark:group-hover:text-gray-400 dark:text-gray-200">
                            {{$wise->sewing_display_name}}
                        </h3>
                        <h3 class="text-xl font-bold text-primary">
                            {{$wise->sewing_line_type}}
                        </h3>
                        <p class="text-sm font-bold">
                            Style : {{$wise->plan->style_id}} <br> Contract : ({{$wise->plan->contract_id}})
                        </p>
                    </div>
                    <div class="ps-3">
                        <x-tabler-shopping-cart-down class="flex-shrink-0 w-8 h-8 text-white" />
                    </div>
                </div>
            </div>
        </a>
        @elseif($wise->status === 'Low')
        <a class="group flex flex-col bg-warning animate-flash animate-infinite border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
            <div class="p-4 md:p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl group-hover:text-primary font-bold text-gray-800 dark:group-hover:text-gray-400 dark:text-gray-200">
                            {{$wise->sewing_display_name}}
                        </h3>
                        <h3 class="text-xl font-bold text-primary">
                            {{$wise->sewing_line_type}}
                        </h3>
                        <p class="text-sm ">
                            Style : {{$wise->plan->style_id}} <br> Contract : ({{$wise->plan->contract_id}})
                        </p>
                    </div>
                    <div class="ps-3">
                        <x-tabler-arrow-autofit-content class="flex-shrink-0 w-8 h-8 text-white" />
                    </div>
                </div>
            </div>
        </a>
        @else
        <a class="group flex flex-col text-white bg-neutral border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" href="#">
            <div class="p-4 md:p-5">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-xl group-hover:text-primary font-bold  dark:group-hover:text-gray-400 dark:text-gray-200">
                            {{$wise->sewing_display_name}}
                        </h3>
                        <h3 class="text-xl font-bold text-primary">
                            {{$wise->sewing_line_type}}
                        </h3>
                        <p class="text-sm ">
                            Style : {{$wise->plan->style_id}} <br> Contract : ({{$wise->plan->contract_id}})
                        </p>
                    </div>
                    <div class="ps-3">
                        <x-tabler-arrow-big-up-line-filled class="flex-shrink-0 w-8 h-8 text-white" />
                    </div>
                </div>
            </div>
        </a>
        @endif
        <!-- End Card -->
        @endforeach
    </div>
    <!-- End Grid -->
</div>
