@extends('layouts.app')

@section('content')
<style>
    @media(prefers-color-scheme: dark) {
        .bg-dots {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(200,200,255,0.15)'/%3E%3C/svg%3E");
        }
    }

    @media(prefers-color-scheme: light) {
        .bg-dots {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,50,0.10)'/%3E%3C/svg%3E")
        }
    }
</style>


<div class="w-screen">
    <div class="p-6 mx-auto max-w-7xl lg:p-8 ">
        <div class="mt-16">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:gap-8">



                <a href="/admin" class="scale-100 p-6 bg-white dark:bg-gray-800/50 dark:bg-gradient-to-bl from-gray-700/50 via-transparent dark:ring-1 dark:ring-inset dark:ring-white/5 rounded-lg shadow-xl shadow-gray-500/20 dark:shadow-none flex motion-safe:hover:scale-[1.01] transition-all duration-250 focus:outline focus:outline-2 focus:outline-indigo-500">
                    <div>
                        <div class="flex items-center justify-center w-16 h-16 rounded-full bg-indigo-50 dark:bg-indigo-900/20">
                            <x-tabler-package class="text-indigo-400" />
                        </div>

                        <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white">Administration Panel</h2>

                        <p class="mt-4 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
                            Administration panel for managing entities.
                        </p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" class="self-center w-6 h-6 mx-6 shrink-0 stroke-indigo-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12h15m0 0l-6.75-6.75M19.5 12l-6.75 6.75" />
                    </svg>
                </a>

            </div>

        </div>

    </div>
</div>
@endsection
