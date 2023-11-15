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


    <x-livewire-alert::scripts />
    @vite('resources/js/app.js')
    @stack('scripts')


</head>

<body class="antialiased bg-base-100">



    <div class="overflow-hidden bg-base-100">

        <main class="w-screen bg-base-100 p-8">
            @yield('content')
        </main>

    </div>



</body>

</html>
