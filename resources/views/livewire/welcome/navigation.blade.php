<div class="navbar bg-base-100 shadow-sm">
    <div class="navbar-start">

        <a class="btn btn-ghost normal-case text-xl"> <img class="w-10 h-10" src="{{ asset('storage/images/teresa-logo-f.png') }}"></a>
    </div>

    <div class="navbar-end">

        <div class="dropdown dropdown-end ">
            @auth
            <label tabindex="0" class="btn btn-ghost">
                <x-heroicon-s-window class="h-5 w-5" />

            </label>
            <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52">



                <li> <a href="{{url('/admin')}}"><x-heroicon-s-key class="h-5 w-5" />Administration</a></li>
                <li><a href="{{url('/accuracy')}}"><x-heroicon-s-clipboard-document-check class="h-5 w-5" />Accuracy</a></li>





            </ul>

            @endauth

        </div>
        <div class="flex justify-center items-center">

            <div class="dropdown dropdown-end ">
                @auth

                <label tabindex="0" class="btn btn-ghost btn-circle avatar">
                    <div class="w-10 rounded-full">
                        <img src="{{ auth()->user()->avatar }}" />
                    </div>
                </label>
                <ul tabindex="0" class="mt-3 z-[1] p-2 shadow menu menu-sm dropdown-content bg-base-100 rounded-box w-52">

                    <li><a href="{{route('logout')}}">Logout</a></li>
                </ul>
                @else
                <a href="{{route('login')}}" class="btn btn-primary">Login</a>

                @endauth
            </div>
            @auth
            <div class="mx-2 text-left">
                <div class="grid text-sm font-extrabold text-primary-500">
                    <div>{{auth()->user()->name}}</div>

                </div>

                <div class="grid text-xs">{{auth()->user()->department}}</div>
            </div>
            @endauth

        </div>


    </div>
</div>