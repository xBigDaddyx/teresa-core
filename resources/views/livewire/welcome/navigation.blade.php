<div class="navbar bg-base-100">
    <div class="navbar-start">
        <div class="dropdown">
            <label tabindex="0" class="btn btn-ghost lg:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                </svg>
            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">
                @auth
                    <li><a>Dashboard</a></li>
                    <li>
                        <a>Application</a>
                        <ul class="p-2">
                            <li><a>Submenu 1</a></li>
                            <li><a>Submenu 2</a></li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
        <a class="btn btn-ghost normal-case text-xl">{{ env('APP_NAME') }}</a>
    </div>
    <div class="navbar-center hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            @auth
                <li><a>Dashboard</a></li>
                <li tabindex="0">
                    <details class="dropdown">
                        <summary>Application</summary>
                        <ul class="p-2 shadow menu dropdown-content z-[1] bg-base-100 rounded-box">
                            <li><a>Submenu 1</a></li>
                            <li><a>Submenu 2</a></li>
                        </ul>
                    </details>
                </li>
            @endauth
        </ul>
    </div>
    <div class="navbar-end">
        @auth
            <div class="dropdown dropdown-end">
                <label tabindex="0" class="btn btn-ghost btn-circle">
                    <div class="indicator">
                    <x-heroicon-o-window class="h-5 w-5"/>
                    </div>
                </label>
                <div tabindex="0" class="mt-3 z-[1] card card-compact dropdown-content w-52 bg-base-100 shadow">
                    <div class="card-body">
                        <div class="card-actions">
                        <a href="{{url('/admin')}}" class="btn btn-primary btn-block">Administration</a>
                        <a href="{{url('/accuracy')}}" class="btn btn-primary btn-block">Accucary</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown dropdown-end">

                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img src="{{ auth()->user()->avatar }}" />
                    </div>
                </label>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">
                    <li>
                        <a class="justify-between" href="{{ route('profile') }}">
                            Profile

                        </a>
                    </li>

                    <li><a>Logout</a></li>
                </ul>
            </div>
        @else
            <a class="btn btn-primary" href="{{ route('login') }}">Log in</a>
        @endauth

    </div>
</div>
