<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="teresa">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{ asset('storage/images/favicon.ico') }}">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap');
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @vite('resources/css/app.css')
    @livewireStyles

    @vite('resources/js/app.js')



</head>

<body class="p-4">
    @php
    $categories = Domain\Purchases\Models\Category::all();
    @endphp
    <!-- Invoice -->
    <div class="max-w-[85rem] mx-auto border border-black">
        <!-- Grid -->
        <div class="flex justify-between items-center dark:border-gray-700">

            <div class="flex items-center space-x-3 p-2">
                <div class="avatar">
                    <div class="w-24 h-24">
                        <img src="{{ asset('storage/'.$detail->company->logo) }}" alt="Logo">
                    </div>
                </div>
                <div>
                    <div class="font-bold">{{$detail->company->name}}</div>
                    <div class="text-xs opacity-75">{!! $detail->company->address !!}</div>
                </div>
            </div>

            <!-- Col -->


        </div>
        <!-- End Grid -->
        <div class="font-bold text-center text-xl border-t border-b border-black">
            PURCHASE REQUEST FORM
        </div>
        <!-- Grid -->
        <div class="grid grid-cols-2 gap-3 mb-8 border-b border-black p-4">
            <div>
                <div class="grid space-y-1">
                    <dl class="grid sm:flex gap-x-3 text-sm">
                        <dt class="min-w-[150px] max-w-[200px] font-bold">
                            Request Date
                        </dt>
                        <dd class="text-gray-800 dark:text-gray-200">
                            <p class="inline-flex items-center gap-x-1.5 font-medium">
                                : {{\Carbon\Carbon::parse($detail->created_at)->format('d/m/Y')}}
                            </p>
                        </dd>
                    </dl>

                    <dl class="grid sm:flex gap-x-3 text-sm">
                        <dt class="min-w-[150px] max-w-[200px] font-bold">
                            Request Number
                        </dt>
                        <dd class="font-medium text-gray-800 dark:text-gray-200">
                            <span class="block font-semibold">: {{$detail->request_number}}</span>

                        </dd>
                    </dl>


                </div>
            </div>
            <!-- Col -->

            <div>
                <div class="grid space-y-1">
                    <h2 class="min-w-[150px] max-w-[200px] font-bold">Request Category :</h2>
                    <div class="grid grid-cols-3 text-xs">

                        @foreach ($categories as $category)
                        <div class="flex flex-nowrap">
                            <input type="checkbox" class="shrink-0 mt-0.5 rounded" id="{{$category->id}}" value="{{$category->id}}" type="checkbox" {{(int)$category->id === (int)$detail->category_id ? 'checked' : 'disabled'}}>
                            <label for="hs-default-checkbox" class="text-nowrap text-xs text-gray-500 ml-3 dark:text-gray-400">{{$category->name}}</label>
                        </div>


                        @endforeach

                    </div>


                </div>
            </div>
            <!-- Col -->
        </div>
        <!-- End Grid -->

        <div class="flex flex-col px-2">
            <div class="sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 sm:px-6 lg:px-8 ">
                    <div class="overflow-hidden border border-black">
                        <table class="min-w-full text-center text-sm font-light">
                            <thead class="border-b font-medium border-black">
                                <tr>
                                    <th scope="col" class="border-r px-6 border-black">
                                        No
                                    </th>
                                    <th scope="col" class="border-r px-6 border-black">
                                        Items
                                    </th>
                                    <th scope="col" class="border-r px-6 border-black">
                                        Specification
                                    </th>
                                    <th scope="col" class="border-r px-6 border-black">
                                        Qty
                                    </th>
                                    <th scope="col" class="border-r px-6 border-black">
                                        Unit
                                    </th>
                                    <th scope="col" class="border-r px-6 border-black">
                                        Delivery Date
                                    </th>
                                    <th scope="col" class="px-6 border-black">
                                        Remark
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detail->requestItems as $item)
                                <tr class="border-black">
                                    <td class="whitespace-nowrap border-r border-black px-6">
                                        {{$loop->iteration}}
                                    </td>
                                    <td class="whitespace-nowrap border-r border-black px-6">
                                        {{$item->product->name}}
                                    </td>
                                    <td class="whitespace-nowrap border-r border-black px-6">
                                        @php

                                        if (count($item->product->specification) > 0) {
                                        $collection = collect($item->product->specification);
                                        $value = $collection->implode(['value'], ' ');
                                        } else {
                                        $value = null;
                                        }
                                        @endphp
                                        {{$value ?? '-'}}
                                    </td>
                                    <td class="whitespace-nowrap border-r border-black px-6">
                                        {{$item->quantity}}
                                    </td>
                                    <td class="whitespace-nowrap border-r border-black px-6">
                                        {{$item->product->unit->name}}
                                    </td>
                                    <td class="whitespace-nowrap border-r border-black px-6">
                                        {{\Carbon\Carbon::parse($item->delivery_date)->format('d/m/Y')}}
                                    </td>
                                    <td class="whitespace-nowrap px-6">
                                        {{$item->remark}}
                                    </td>
                                </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-3 mb-8 p-4">
            <div>
                <div class="grid space-y-1">
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="min-w-[150px] max-w-[200px] font-bold">
                            For Customer / Buyer
                        </dt>
                        <dd class="text-gray-800 dark:text-gray-200">
                            <p class="inline-flex items-center gap-x-1.5 font-medium">
                                : {{$detail->customer}}
                            </p>
                        </dd>
                    </dl>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="min-w-[150px] max-w-[200px] font-bold">
                            Contract No
                        </dt>
                        <dd class="text-gray-800 dark:text-gray-200">
                            <p class="inline-flex items-center gap-x-1.5 font-medium">
                                : {{$detail->contract_no}}
                            </p>
                        </dd>
                    </dl>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="min-w-[150px] max-w-[200px] font-bold">
                            Last Request Record
                        </dt>
                        <dd class="text-gray-800 dark:text-gray-200">
                            <p class="inline-flex items-center gap-x-1.5 font-medium">
                                :
                            </p>
                        </dd>
                    </dl>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="min-w-[150px] max-w-[200px] font-bold">
                            With Budget or Not
                        </dt>
                        <dd class="text-gray-800 dark:text-gray-200">
                            <p class="inline-flex items-center gap-x-1.5 font-medium">
                                :
                            </p>
                        </dd>
                    </dl>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="min-w-[150px]  font-bold">
                            If Without Budget Give Explanation
                        </dt>

                    </dl>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="max-w-full min-w-full h-[150px] p-4 border border-black font-bold">

                        </dt>

                    </dl>
                </div>
            </div>
            <div>
                <div class="grid space-y-1">
                    <h2 class="min-w-[150px] max-w-[200px] font-bold">Fill by Finance :</h2>
                    <div class="grid grid-cols-1 text-xs">


                        <div class="flex flex-nowrap">
                            <input type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="not-approved" value="not-approved" type="checkbox" disabled>
                            <label for="not-approved" class="text-nowrap text-xs text-gray-500 ml-3 dark:text-gray-400">Not Approved & PR is Returned to User</label>
                        </div>
                        <div class="flex flex-nowrap">
                            <input type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="approved-without-note" value="approved-without-note" type="checkbox" disabled>
                            <label for="approved-without-note" class="text-nowrap text-xs text-gray-500 ml-3 dark:text-gray-400">Approved Without Notes</label>
                        </div>
                        <div class="flex flex-nowrap">
                            <input type="checkbox" class="shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 pointer-events-none focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800" id="approved-with-note" value="approved-with-note" type="checkbox" disabled>
                            <label for="approved-with-note" class="text-nowrap text-xs text-gray-500 ml-3 dark:text-gray-400">Approved With Notes</label>
                        </div>


                    </div>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="min-w-[150px]  font-bold">
                            Notes
                        </dt>

                    </dl>
                    <dl class="grid sm:flex gap-x-3 text-xs">
                        <dt class="max-w-full min-w-full h-[150px] p-4 border border-black font-bold">
                            {{$detail->note}}
                        </dt>

                    </dl>

                </div>
            </div>
        </div>
    </div>
    <!-- End Invoice -->
</body>

</html>
