<div class="navbar bg-base-100 shadow-sm">
    <div class="navbar-start">
        <a class="btn btn-ghost normal-case text-xl"> <img class="w-10 h-10" src="{{ asset('storage/images/teresa-logo-f.png') }}"></a>
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li>
                    <details>
                        <summary class="hover:bg-primary">
                            Accuracy
                        </summary>
                        <ul class="p-2 bg-base-100">
                            <li><a class="hover:bg-primary" href="{{ route('accuracy.check.carton') }}">
                                    <x-heroicon-o-magnifying-glass-circle class="h-5 w-5" />
                                    Validation
                                </a></li>
                            <li><a class="hover:bg-primary">
                                    <x-heroicon-o-document-magnifying-glass class="h-5 w-5" />
                                    Inspection
                                    <span class="badge badge-sm badge-error">Coming Soon</span>
                                </a></li>
                        </ul>
                    </details>
                </li>
            </ul>
        </div>
    </div>

    <div class="navbar-end">
        @can('panel.viewAny')
        <div class="dropdown dropdown-end ">
            <label tabindex="0" class="btn btn-ghost">
                <x-heroicon-s-window class="h-5 w-5" />
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                <li> <a href="{{ route('filament.admin.tenant') }}"><x-heroicon-s-key class="h-5 w-5" />Administration
                        Panel</a></li>
                <li><a href="{{ route('filament.accuracy.tenant') }}"><x-heroicon-s-clipboard-document-check class="h-5 w-5" />Accuracy Panel</a></li>
            </ul>
        </div>
        @endcan

        <div class="flex justify-center items-center">

            <div class="dropdown dropdown-end ">
                @auth
                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img src="{{ auth()->user()->avatar }}" />
                    </div>
                </label>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                    <li><a href="{{ route('logout') }}">Logout</a></li>
                </ul>
                @else
                <!-- <a href="{{ route('login') }}" class="btn btn-primary">Login</a> -->
                @endauth
            </div>
            @auth
            <div class="mx-2 text-left">
                <div class="grid text-sm font-extrabold text-primary">
                    <div>{{ auth()->user()->name }}</div>
                </div>
                <div class="grid text-xs">{{ auth()->user()->department }}</div>
            </div>
            @endauth

        </div>


    </div>
</div>
