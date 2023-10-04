<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">
@if (Route::has('login'))
            <livewire:welcome.navigation />
        @endif
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">


        <!-- Hero -->
        <div class="max-w-[85rem] mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Grid -->
            <div class="grid md:grid-cols-2 gap-4 md:gap-8 xl:gap-20 md:items-center">
                <div>
                    <h1
                        class="block text-3xl font-bold text-gray-800 sm:text-4xl lg:text-6xl lg:leading-tight dark:text-white">
                        Start your journey with <span class="text-primary-400 font-extrabold">{{env('APP_NAME')}}</span></h1>
                    <p class="mt-3 text-lg text-gray-800 dark:text-gray-400">Crafted with love ❤️</p>

                    <!-- Buttons -->
                    <div class="mt-7 grid gap-3 w-full sm:inline-flex">
                        <a class="inline-flex justify-center items-center gap-x-3 text-center bg-primary-400 hover:bg-primary-500 border border-transparent text-sm lg:text-base text-white font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-primary-400 focus:ring-offset-2 focus:ring-offset-white transition py-3 px-4 dark:focus:ring-offset-gray-800"
                            href="{{route('login')}}">
                            Get started
                            <svg class="w-2.5 h-2.5" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path
                                    d="M5.27921 2L10.9257 7.64645C11.1209 7.84171 11.1209 8.15829 10.9257 8.35355L5.27921 14"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                            </svg>
                        </a>

                    </div>
                    <!-- End Buttons -->


                </div>
                <!-- End Col -->

                <div class="relative ml-4">

                    <img class="w-full rounded-md"
                        src="{{ asset('storage/images/illu01.png') }}"
                        alt="Image Description">

                </div>
                <!-- End Col -->
            </div>
            <!-- End Grid -->
        </div>
        <!-- End Hero -->
    </div>
</body>

</html>
