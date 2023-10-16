<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="mytheme">

<head>
    <meta charset="utf-8">
    <link rel="shortcut icon" href="{{ asset('storage/images/favicon.ico') }}">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>{{ config('app.name') }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css?family=Nunito:400,700&display=swap');
    </style>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
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
    @vite('resources/css/app.css')
    @livewireStyles

    @livewireScripts
    @livewire('notifications')

    @vite('resources/js/app.js')
    @stack('scripts')


</head>

<body class="antialiased bg-primary-50 dark:bg-[#2D2F41] h-screen">

    <x-toast />

    <div class="overflow-hidden ">
        @include('livewire.layout.navbar')
        <main class="w-screen">
            @yield('content')
        </main>

    </div>



</body>

<script>
    const HSThemeAppearance = {
        init() {
            const defaultTheme = 'default'
            let theme = localStorage.getItem('hs_theme') || defaultTheme

            if (document.querySelector('html').classList.contains('dark')) return
            this.setAppearance(theme)
        },
        _resetStylesOnLoad() {
            const $resetStyles = document.createElement('style')
            $resetStyles.innerText = `*{transition: unset !important;}`
            $resetStyles.setAttribute('data-hs-appearance-onload-styles', '')
            document.head.appendChild($resetStyles)
            return $resetStyles
        },
        setAppearance(theme, saveInStore = true, dispatchEvent = true) {
            const $resetStylesEl = this._resetStylesOnLoad()

            if (saveInStore) {
                localStorage.setItem('hs_theme', theme)
            }

            if (theme === 'auto') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'
            }

            document.querySelector('html').classList.remove('dark')
            document.querySelector('html').classList.remove('default')
            document.querySelector('html').classList.remove('auto')

            document.querySelector('html').classList.add(this.getOriginalAppearance())

            setTimeout(() => {
                $resetStylesEl.remove()
            })

            if (dispatchEvent) {
                window.dispatchEvent(new CustomEvent('on-hs-appearance-change', {
                    detail: theme
                }))
            }
        },
        getAppearance() {
            let theme = this.getOriginalAppearance()
            if (theme === 'auto') {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default'
            }
            return theme
        },
        getOriginalAppearance() {
            const defaultTheme = 'default'
            return localStorage.getItem('hs_theme') || defaultTheme
        }
    }
    HSThemeAppearance.init()

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', e => {
        if (HSThemeAppearance.getOriginalAppearance() === 'auto') {
            HSThemeAppearance.setAppearance('auto', false)
        }
    })

    window.addEventListener('load', () => {
        const $clickableThemes = document.querySelectorAll('[data-hs-theme-click-value]')
        const $switchableThemes = document.querySelectorAll('[data-hs-theme-switch]')

        $clickableThemes.forEach($item => {
            $item.addEventListener('click', () => HSThemeAppearance.setAppearance($item.getAttribute('data-hs-theme-click-value'), true, $item))
        })

        $switchableThemes.forEach($item => {
            $item.addEventListener('change', (e) => {
                HSThemeAppearance.setAppearance(e.target.checked ? 'dark' : 'default')
            })

            $item.checked = HSThemeAppearance.getAppearance() === 'dark'
        })

        window.addEventListener('on-hs-appearance-change', e => {
            $switchableThemes.forEach($item => {
                $item.checked = e.detail === 'dark'
            })
        })
    })
</script>

</html>