<?php

use App\Providers\RouteServiceProvider;
use Domain\Users\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    #[Rule(['required', 'string', 'email'])]
    public string $email = '';

    #[Rule(['required', 'string'])]
    public string $password = '';

    #[Rule(['boolean'])]
    public bool $remember = false;

    // public function redirectToAzure()
    // {

    //     return Socialite::driver('azure')->redirect();
    // }

    // public function handleCallback()
    // {
    //     try {
    //         // get user data from Google
    //         $user = Socialite::driver('azure')->user();

    //         // find user in the database where the social id is the same with the id provided by Google
    //         $finduser = User::where('social_id', $user->id)->first();

    //         if ($finduser) {  // if user found then do this
    //             // Log the user in
    //             Auth::login($finduser);

    //             // redirect user to dashboard page
    //             return redirect('/dashboard');
    //         } else {
    //             // if user not found then this is the first time he/she try to login with Google account
    //             // create user data with their Google account data
    //             $newUser = User::create([
    //                 'name' => $user->name,
    //                 'email' => $user->email,
    //                 'social_id' => $user->id,
    //                 'social_type' => 'azure',  // the social login is using google
    //                 'password' => bcrypt('C@rtini#5'),  // fill password by whatever pattern you choose
    //             ]);

    //             Auth::login($newUser);

    //             return redirect('/dashboard');
    //         }

    //     } catch (Exception $e) {
    //         dd($e->getMessage());
    //     }
    // }

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (!auth()->attempt($this->only(['email', 'password'], $this->remember))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        session()->regenerate();

        $this->redirect(session('url.intended', RouteServiceProvider::HOME), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
    }
}; ?>

<div class="p-4 sm:p-7">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <div class="text-center" class="mb-4">
        <h1 class="block text-2xl font-bold text-gray-800 dark:text-white">Sign in</h1>

    </div>
    <div class="mt-5">
        <a href="{{ url('auth/redirect') }}">
            <button type="button" class="w-full py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border font-medium bg-white text-gray-700 shadow-sm align-middle hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-primary-600 transition-all text-sm dark:bg-gray-800 dark:hover:bg-slate-800 dark:border-gray-700 dark:text-gray-400 dark:hover:text-white dark:focus:ring-offset-gray-800">
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
        <div class="py-3 flex items-center text-xs text-gray-400 uppercase before:flex-[1_1_0%] before:border-t before:border-gray-200 before:mr-6 after:flex-[1_1_0%] after:border-t after:border-gray-200 after:ml-6 dark:text-gray-500 dark:before:border-gray-600 dark:after:border-gray-600">
            Or</div>
        <form wire:submit="login">

            <div class="grid gap-y-4">
                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input wire:model="email" id="email" class="block mt-1 w-full" type="email" name="email" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <x-input-label for="password" :value="__('Password')" />

                    <x-text-input wire:model="password" id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember" class="inline-flex items-center">
                        <input wire:model="remember" id="remember" type="checkbox" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-primary-600 shadow-sm focus:ring-primary-500 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800" name="remember">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('Forgot your password?') }}
                    </a>
                    @endif



                </div>
                <button type="submit" class="btn btn-primary">
                    {{ __('Log in') }}
                </button>
                <!-- <button type="submit"
                    class="py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-primary-500 text-white hover:bg-primary-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800">
                    {{ __('Log in') }}</button> -->

            </div>


        </form>
    </div>


</div>
