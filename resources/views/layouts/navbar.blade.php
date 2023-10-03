<header class="flex flex-wrap md:justify-start md:flex-nowrap z-50 w-full text-sm py-3 md:py-0">
    <nav class="max-w-[85rem] w-full mx-auto px-4 md:px-6 lg:px-8" aria-label="Global">
        <div class="relative md:flex md:items-center md:justify-between">
            <div class="flex items-center justify-between">
                <a class="flex-none" href="{{ route('home') }}">
                    <img class="w-28 h-auto" src="{{ asset('storage/images/teresa-logo.png') }}" alt="" title="">
                </a>

                <div class="md:hidden">
                    <button type="button"
                        class="hs-collapse-toggle p-2 inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-violet-900 transition-all text-sm dark:bg-slate-900 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800"
                        data-hs-collapse="#navbar-collapse-with-animation"
                        aria-controls="navbar-collapse-with-animation" aria-label="Toggle navigation">
                        <svg class="hs-collapse-open:hidden w-4 h-4" width="16" height="16" fill="currentColor"
                            viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                        </svg>
                        <svg class="hs-collapse-open:block hidden w-4 h-4" width="16" height="16"
                            fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                        </svg>
                    </button>
                </div>
            </div>

            <div id="navbar-collapse-with-animation"
                class="hs-collapse hidden overflow-hidden transition-all duration-300 basis-full grow md:block">
                <div class="overflow-hidden overflow-y-auto max-h-[75vh] scrollbar-y">
                    <div
                        class="flex flex-col gap-x-0 mt-5 divide-y divide-dashed divide-gray-200 md:flex-row md:items-center md:justify-end md:gap-x-7 md:mt-0 md:pl-7 md:divide-y-0 md:divide-solid dark:divide-gray-700">


                        @if (Route::has('login'))

                            @auth

                                <a href="{{ route('home') }}"
                                    class="flex items-center gap-x-2 font-medium text-gray-600 hover:text-primary-500 py-3 md:py-6 dark:text-gray-400 dark:hover:text-gray-500"
                                    aria-current="page">
                                    <x-heroicon-o-home class="w-4 h-4"/> Home
                                </a>
                                @if(Route::has('filament.admin.tenant'))
                                <a href="{{ route('filament.admin.tenant') }}"
                                    class="flex items-center gap-x-2 font-medium text-gray-600 hover:text-primary-500 py-3 md:py-6 dark:text-gray-400 dark:hover:text-gray-500"
                                    aria-current="page">
                                    <x-heroicon-o-key class="w-4 h-4"/> Admin
                                </a>
                            @endif
                                <!-- <button wire:click="dataShow"
                                        class="font-semibold text-sm bg-success-500 text-white rounded-md shadow-sm px-4 py-2 inline-flex space-x-1 items-center">
                                        <span>
                                            <x-tabler-file />
                                        </span>
                                        <span>History</span>
                                    </button> -->
                                    <a
                                    class="flex items-center gap-x-2 font-medium text-gray-500 hover:text-primary-500 sm:border-l sm:border-gray-300 sm:my-6 sm:pl-6 dark:border-gray-700 dark:text-gray-400 dark:hover:text-blue-500" href="#"
                                    aria-current="page">
                                    <x-heroicon-o-user class="w-4 h-4"/>
                                    {{auth()->user()->name}}
                                </a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="font-medium text-gray-600 hover:text-gray-500 py-3 md:py-6 dark:text-gray-400 dark:hover:text-gray-500"
                                    aria-current="page">Log
                                    in</a>

                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
                                        class="font-medium text-gray-600 hover:text-gray-500 py-3 md:py-6 dark:text-gray-400 dark:hover:text-gray-500"
                                        aria-current="page">Register</a>
                                @endif
                            @endauth

                            <a class="hs-dark-mode-active:hidden block hs-dark-mode group flex items-center text-gray-600 hover:text-primary-500 font-medium dark:text-gray-400 dark:hover:text-gray-500"
                            href="#!" data-hs-theme-click-value="dark">
                            <x-heroicon-o-moon class="w-4 h-4"/>
                            <!-- <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278zM4.858 1.311A7.269 7.269 0 0 0 1.025 7.71c0 4.02 3.279 7.276 7.319 7.276a7.316 7.316 0 0 0 5.205-2.162c-.337.042-.68.063-1.029.063-4.61 0-8.343-3.714-8.343-8.29 0-1.167.242-2.278.681-3.286z" />
                            </svg> -->
                        </a>
                        <a class="hs-dark-mode-active:block hidden hs-dark-mode group flex items-center text-gray-600 hover:text-primary-500 font-medium dark:text-gray-400 dark:hover:text-gray-500"
                            href="#!" data-hs-theme-click-value="light">
                            <x-heroicon-o-sun class="w-4 h-4"/>
                            <!-- <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                fill="currentColor" viewBox="0 0 16 16">
                                <path
                                    d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0 1a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z" />
                            </svg> -->
                        </a>



                        @endif

                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
