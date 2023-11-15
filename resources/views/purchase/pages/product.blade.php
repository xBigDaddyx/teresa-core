@extends('purchase.layouts.app')

@section('content')
<!-- Features -->
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto ">
    <!-- Grid -->
    <div class="lg:grid lg:grid-cols-12 lg:gap-16 lg:items-center">
        <div class="lg:col-span-7">
            <!-- Grid -->
            <div class="grid grid-cols-12 gap-2 sm:gap-6 items-center lg:-translate-x-10">

                @foreach ($detail->getMedia('products') as $photo)
                <div class="col-span-5">
                    <img class="rounded-xl" src="{{$photo->getUrl()}}" alt="Image Description">
                </div>
                @endforeach


                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </div>
        <!-- End Col -->

        <div class="mt-5 sm:mt-10 lg:mt-0 lg:col-span-5">
            <div class="space-y-6 sm:space-y-8">
                <!-- Title -->
                <div class="space-y-2 md:space-y-4">
                    <h2 class="font-bold text-3xl lg:text-4xl text-gray-800 dark:text-gray-200">
                        {{$detail->name}}
                        <div class="badge badge-primary">{{$detail->category->name}}</div>
                    </h2>
                    <p class="text-gray-500">
                        {{$detail->remark}}
                    </p>
                </div>
                <!-- End Title -->

                <!-- List -->
                <ul role="list" class="space-y-2 sm:space-y-4">
                    @foreach ($detail->specification as $spec)
                    <li class="flex space-x-3">
                        <!-- Solid Check -->
                        <input type="checkbox" checked="checked" class="checkbox checkbox-primary" />

                        <!-- End Solid Check -->

                        <span class="text-sm sm:text-base text-gray-500">
                            <span class="font-bold">{{$spec['category']}} :</span> {{$spec['value']}}
                        </span>
                    </li>
                    @endforeach
                </ul>
                <!-- End List -->
            </div>
        </div>
        <!-- End Col -->
    </div>
    <!-- End Grid -->
</div>
<!-- End Features -->

@endsection
