@extends('accuracy.layouts.app')

@section('content')
<!-- Hero -->
<div class="max-w-[78rem] mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Grid -->
    <div class="grid md:grid-cols-2 gap-4 md:gap-8 xl:gap-20 md:items-center">

        <div>
            <h1 class="block text-md text-gray-800 lg:leading-tight dark:text-white ">PO : <span class="font-bold text-error">{{$carton->packingList->po}}</span>🗒 Box Code : <span class="font-bold text-error">{{$carton->box_code}}📝</span></h1>
            <h1 class="block text-3xl font-bold text-gray-800 sm:text-4xl lg:text-6xl lg:leading-tight dark:text-white">This Carton Box 📦 <span class="text-primary">Completed!</span></h1>
            <p class="mt-3 text-lg text-gray-800 dark:text-gray-400">Completed by <span class="font-bold text-error">{{$user->name}}</span> at <span class="font-bold text-error">{{\Carbon\Carbon::parse($carton->completed_at)->format('d M Y H:i:s')}}</span></p>

            <!-- Buttons -->
            <div class="mt-7 grid gap-3 w-full sm:inline-flex">
                <x-button link="/accuracy/carton/check" label="Back" icon="o-arrow-left" tooltip-left="Back" class="btn btn-primary inline-flex justify-center items-center gap-x-3 text-sm text-white font-medium rounded-md transition py-3 px-4" />

            </div>
            <!-- End Buttons -->


        </div>
        <!-- End Col -->

        <div class="relative ml-4 hidden md:block">
            <img class="max-w-sm rounded-md" src="{{ asset('storage/images/carton-box-illu05.jpg') }}" alt="Image Description">
            <div class="absolute inset-0 -z-[1] bg-gradient-to-tr from-gray-200 via-white/0 to-white/0 w-full h-full rounded-md mt-4 -mb-4 mr-4 -ml-4 lg:mt-6 lg:-mb-6 lg:mr-6 lg:-ml-6 dark:from-slate-800 dark:via-slate-900/0 dark:to-slate-900/0"></div>


        </div>
        <!-- End Col -->
    </div>
    <!-- End Grid -->
</div>
<!-- End Hero -->

@endsection
