<?php

namespace Domain\Admin\Models;

use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Exception;
use Filament\Facades\Filament;
use Filament\Pages\Auth\Login as AuthLogin;
use Filament\Panel as FilamentPanel;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Models\Contracts\FilamentUser;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use LdapRecord\Laravel\Auth\ListensForLdapBindFailure;

class Login extends AuthLogin
{
    // use ListensForLdapBindFailure;
    // protected function handleLdapBindError($message, $code = null)
    // {
    //     if ($code == '773') {
    //         // The users password has expired. Redirect them.
    //         abort(redirect('/password-reset'));
    //     }

    //     throw ValidationException::withMessages([
    //         'data.email' => "Whoops! LDAP server cannot be reached.",
    //     ]);
    // }
    protected function throwLoginValidationException(string $message): void
    {
        $username = 'data.email';

        if (class_exists($fortify = 'Laravel\Fortify\Fortify')) {
            $username = $fortify::username();
        } elseif (method_exists($this, 'username')) {
            $username = $this->username();
        } elseif (property_exists($this, 'username')) {
            $username = $this->username;
        }

        throw ValidationException::withMessages([
            $username => $message,
        ]);
    }
    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'email' => $data['email'],
            'password' => $data['password'],
        ];
    }
    protected function getCredentialsFromFormDataForLdap(array $data): array
    {
        return [
            'mail' => $data['email'],
            'password' => $data['password'],
        ];
    }
    public function authenticate(): ?LoginResponse
    {
        try {
            $this->rateLimit(5);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/login.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/login.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        if (!Filament::auth()->attempt($this->getCredentialsFromFormDataForLdap($data), $data['remember'] ?? false)) {
            $this->throwFailureValidationException();
        }

        $user = Filament::auth()->user();

        if (
            ($user instanceof FilamentUser) &&
            (!$user->canAccessPanel(Filament::getCurrentPanel()))
        ) {
            Filament::auth()->logout();

            $this->throwFailureValidationException();
        }

        session()->regenerate();

        return app(LoginResponse::class);
    }
    // public function authenticate(): ?LoginResponse
    // {
    //     try {
    //         $this->rateLimit(5);
    //     } catch (TooManyRequestsException $exception) {
    //         Notification::make()
    //             ->title(__('filament-panels::pages/auth/login.notifications.throttled.title', [
    //                 'seconds' => $exception->secondsUntilAvailable,
    //                 'minutes' => ceil($exception->secondsUntilAvailable / 60),
    //             ]))
    //             ->body(array_key_exists(
    //                 'body',
    //                 __('filament-panels::pages/auth/login.notifications.throttled') ?: []
    //             ) ? __(
    //                 'filament-panels::pages/auth/login.notifications.throttled.body',
    //                 [
    //                     'seconds' => $exception->secondsUntilAvailable,
    //                     'minutes' => ceil($exception->secondsUntilAvailable / 60),
    //                 ]
    //             ) : null)
    //             ->danger()
    //             ->send();

    //         return null;
    //     }

    //     $data = $this->form->getState();

    //     $guard = null;
    //     // try {
    //     //     if (Auth::guard('ldap')
    //     //         ->attempt(
    //     //             $this->getCredentialsFromFormDataForLdap($data),
    //     //             $data['remember'] ?? false
    //     //         )
    //     //     ) {
    //     //         $guard = 'ldap';
    //     //     }
    //     //     if (Auth::guard('web')
    //     //         ->attempt(
    //     //             $this->getCredentialsFromFormData($data),
    //     //             $data['remember'] ?? false
    //     //         )
    //     //     ) {
    //     //         $guard = 'web';
    //     //     }
    //     //     return $this->throwLoginValidationException(__('ldap::errors.user_not_found'));
    //     // } catch (Exception $e) {
    //     //     dd($e);
    //     // }
    //     if (!Auth::guard('ldap')
    //         ->attempt(
    //             $this->getCredentialsFromFormDataForLdap($data),
    //             $data['remember'] ?? false
    //         )) {
    //         if (!Auth::guard('web')
    //             ->attempt(
    //                 $this->getCredentialsFromFormData($data),
    //                 $data['remember'] ?? false
    //             )) {

    //             $this->throwFailureValidationException();
    //         } else {
    //             $guard = 'web';
    //         }
    //     } else {
    //         $guard = 'ldap';
    //     }

    //     Auth::shouldUse($guard);
    //     Filament::getCurrentPanel()->authGuard($guard);
    //     $user = Auth::guard($guard)->user();

    //     if (
    //         ($user instanceof FilamentUser) &&
    //         (!$user->canAccessPanel(Filament::getCurrentPanel()))
    //     ) {
    //         Filament::auth()->logout();

    //         $this->throwFailureValidationException();
    //     }

    //     session()->regenerate();

    //     if (!is_null($guard)) {
    //         session(['guard' => $guard]);
    //     }

    //     return app(LoginResponse::class);
    // }

    protected function throwFailureValidationException(): never
    {

        throw ValidationException::withMessages([
            'data.email' => __('ldap::errors.user_not_found'),
        ]);
    }
    protected static string $view = 'Admin.Auth.login';
}
