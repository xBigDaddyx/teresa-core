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
Route::get('/auth/redirect', function () {
    return Socialite::driver('azure')->redirect();
});
Route::get('/logout', function (Request $request) {
    Auth::guard()->logout();
    $request->session()->flush();
    $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
    return redirect($azureLogoutUrl);
})->middleware('auth')->name('logout');
Route::get('/auth/callback', function () {
    try {
        // get user data from Google
        $user = Socialite::driver('azure')->user();

        // find user in the database where the social id is the same with the id provided by Google
        $findUser = User::where('social_id', $user->id)->orWhere('email', $user->email)->first();

        if ($findUser) {
            Auth::login($findUser);
            if ($findUser->social_id === null || empty($findUser->social_id)) {
                $findUser->social_id = $user->id;
                $findUser->social_type = 'azure';
                $findUser->save();
            }

            return redirect()->route('index');
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

Route::view('menu', 'livewire.menu.menu')
    ->middleware(['auth', 'verified'])
    ->name('menu');
