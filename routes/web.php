<?php

use Domain\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome')->name('index');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

Route::get('/logout', function (Request $request) {
    Auth::guard()->logout();
    $request->session()->flush();
    $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('index'));
    return redirect($azureLogoutUrl);
})->middleware('auth')->name('logout');

Route::domain('{panel}.core.test')->group(function () {
    Route::get('/auth/redirect', function (string $panel) {
        $getConfig = new \SocialiteProviders\Manager\Config(
            env('AZURE_CLIENT_ID', 'some-client-id'), // a different clientID for this separate Azure directory
            env('AZURE_CLIENT_SECRET'), // a different secret for this separate Azure directory
            'https://' . $panel . '.core.test/auth/callback', // the redirect path i.e. a different callback to the other azureAD callbacks
            ['tenant' => env('AZURE_TENANT_ID')], // this could be something special if need be, but can also be left out entirely
        );
        return Socialite::driver('azure')->setConfig($getConfig)->redirect();
    });
    Route::get('/auth/callback', function (string $panel) {
        try {
            $getConfig = new \SocialiteProviders\Manager\Config(
                env('AZURE_CLIENT_ID', 'some-client-id'), // a different clientID for this separate Azure directory
                env('AZURE_CLIENT_SECRET'), // a different secret for this separate Azure directory
                'https://' . $panel . '.core.test/auth/callback', // the redirect path i.e. a different callback to the other azureAD callbacks
                ['tenant' => env('AZURE_TENANT_ID')], // this could be something special if need be, but can also be left out entirely
            );
            // get user data from Google
            $user = Socialite::driver('azure')->setConfig($getConfig)->user();

            // find user in the database where the social id is the same with the id provided by Google
            $findUser = User::where('social_id', $user->id)->orWhere('email', $user->email)->first();

            if ($findUser) {
                Auth::login($findUser);
                if ($findUser->social_id === null || empty($findUser->social_id)) {
                    $findUser->social_id = $user->id;
                    $findUser->social_type = 'azure';
                    $findUser->save();
                }

                return redirect()->route('filament.' . $panel . '.tenant');
            }

            return abort(403);
            // $usermodel = User::updateOrCreate(
            //     ['email' => $user->email],
            //     ['social_type' => 'azure', 'social_id' => $user->id]
            // );

            // Auth::login($usermodel);

            // return redirect()->route('menu');

        } catch (Exception $e) {
            dd($e->getMessage());
        }

        // $user->token
    });
});

Route::view('menu', 'livewire.menu.menu')
    ->middleware(['auth', 'verified'])
    ->name('menu');
