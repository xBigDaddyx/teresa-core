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
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">


        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <!-- Card Section -->
            <div class="max-w-5xl px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
                <!-- Grid -->
                <div class="grid sm:grid-cols-2 lg:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Card -->
                    <a class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800"
                        href="/dashboard">
                        <div class="p-4 md:p-5">
                            <div class="flex">
                            <x-heroicon-o-window class="mt-1 shrink-0 w-5 h-5 text-gray-800 dark:text-gray-200"/>

                                <div class="grow ml-5">
                                    <h3
                                        class="group-hover:text-blue-600 font-semibold text-gray-800 dark:group-hover:text-gray-400 dark:text-gray-200">
                                        Application
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                    You will be presented with various modules
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- End Card -->

                    <!-- Card -->
                    <a class="group flex flex-col bg-white border shadow-sm rounded-xl hover:shadow-md transition dark:bg-slate-900 dark:border-gray-800"
                        href="/admin">
                        <div class="p-4 md:p-5">
                            <div class="flex">
                            <x-heroicon-o-clipboard-document-list class="mt-1 shrink-0 w-5 h-5 text-gray-800 dark:text-gray-200"/>


                                <div class="grow ml-5">
                                    <h3
                                        class="group-hover:text-blue-600 font-semibold text-gray-800 dark:group-hover:text-gray-400 dark:text-gray-200">
                                        Administration
                                    </h3>
                                    <p class="text-sm text-gray-500">
                                        Panel for administration
                                    </p>
                                </div>
                            </div>
                        </div>
                    </a>
                    <!-- End Card -->


                </div>
                <!-- End Grid -->
            </div>
            <!-- End Card Section -->
        </div>
    </div>
</body>

</html>
