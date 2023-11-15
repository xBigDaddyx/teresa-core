<?php

use App\Http\Controllers\CartonBoxCheckController;
use App\Http\Controllers\Kanban\DashboardController;
use App\Http\Controllers\Kanban\PlanQueueController;
use App\Http\Controllers\PolybagValidationController;
use App\Http\Controllers\Purchase\ProductController;
use App\Http\Controllers\Purchase\RequestDocumentController;
use App\Http\Controllers\ValidationController;
use App\Mail\SendPlanQueueNotification;
use App\Mail\SendRequestApprovalNotification;
use App\Mail\SendRequestApprovedNotification;
use App\Mail\SendRequestSubmitedNotification;
use Domain\Kanban\Models\PlanQueue;
use Domain\Purchases\Models\ApprovalRequest;
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


Route::get('{panel}/auth/redirect', function (string $panel) {
    $getConfig = new \SocialiteProviders\Manager\Config(
        env('AZURE_CLIENT_ID', 'some-client-id'), // a different clientID for this separate Azure directory
        env('AZURE_CLIENT_SECRET'), // a different secret for this separate Azure directory
        'https://teresa.hoplun.com/' . $panel . '/auth/callback', // the redirect path i.e. a different callback to the other azureAD callbacks
        ['tenant' => env('AZURE_TENANT_ID')], // this could be something special if need be, but can also be left out entirely
    );
    return Socialite::driver('azure')->setConfig($getConfig)->redirect();
})->name('azure.redirect');
Route::get('{panel}/auth/callback', function (string $panel) {
    try {
        $getConfig = new \SocialiteProviders\Manager\Config(
            env('AZURE_CLIENT_ID', 'some-client-id'), // a different clientID for this separate Azure directory
            env('AZURE_CLIENT_SECRET'), // a different secret for this separate Azure directory
            'https://teresa.hoplun.com/' . $panel . '/auth/callback', // the redirect path i.e. a different callback to the other azureAD callbacks
            ['tenant' => env('AZURE_TENANT_ID')], // this could be something special if need be, but can also be left out entirely
        );
        // get user data from Google
        $user = Socialite::driver('azure')->setConfig($getConfig)->user();

        // find user in the database where the social id is the same with the id provided by Google
        $findUser = User::where('social_id', $user->id)->orWhere('email', $user->email)->first();

        if ($findUser) {
            Auth::guard('ldap')->login($findUser);
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
})->name('azure.callback');

Route::middleware('auth')->prefix('accuracy')->group(function () {
    Route::get('/{carton}/completed', [PolybagValidationController::class, 'completed'])->name('accuracy.completed.carton');
    Route::get('/carton/check', [CartonBoxCheckController::class, 'index'])->name('accuracy.check.carton');
    Route::get('/{carton}/polybag', [PolybagValidationController::class, 'index'])->name('accuracy.validation.polybag');
});
Route::middleware('auth')->prefix('kanban')->group(function () {
    Route::get('/{company}/dashboard', [DashboardController::class, 'index'])->name('kanban.dashboard');
});
Route::middleware('auth')->prefix('purchase')->group(function () {
    Route::get('/request/{record}/doc', [RequestDocumentController::class, 'index'])->name('request.document.report');
});
Route::middleware('auth')->prefix('purchase')->group(function () {
    Route::get('/product/{record}', [ProductController::class, 'index'])->name('product.view.index');
});


Route::view('menu', 'livewire.menu.menu')
    ->middleware(['auth', 'verified'])
    ->name('menu');

Route::get('/mailable', function () {
    $invoice = PlanQueue::find(55);

    return new SendPlanQueueNotification(User::find(381), $invoice);
});

Route::get('/plan-queue/{oldQueue}/{newQueue}', [PlanQueueController::class, 'switch'])->name('plan.queue.switch')->middleware('signed');
Route::get('/sign/{oldQueue}/{newQueue}', [PlanQueueController::class, 'action']);
