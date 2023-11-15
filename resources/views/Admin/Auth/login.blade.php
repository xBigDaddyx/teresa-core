<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
    <x-slot name="subheading">
        {{ __('filament-panels::pages/auth/login.actions.register.before') }}

        {{ $this->registerAction }}
    </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}

    <a href="{{ url(\Filament\Facades\Filament::getCurrentPanel()->getPath().'/auth/redirect') }}">
        <button type="button" class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-blue-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-auto" width="46" height="47" viewBox="0 0 256 256">
                <rect x="0" y="0" width="256" height="256" fill="none" stroke="none" />
                <path fill="#F1511B" d="M121.666 121.666H0V0h121.666z" />
                <path fill="#80CC28" d="M256 121.666H134.335V0H256z" />
                <path fill="#00ADEF" d="M121.663 256.002H0V134.336h121.663z" />
                <path fill="#FBBC09" d="M256 256.002H134.335V134.336H256z" />
            </svg>
            Sign in with Microsoft
        </button>

    </a>

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.after') }}
</x-filament-panels::page.simple>
