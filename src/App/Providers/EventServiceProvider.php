<?php

namespace App\Providers;

use App\Events\MakeOrderEvent;
use App\Events\RequestApproved;
use App\Events\RequestSubmited;
use App\Listeners\MakeOrderListener;
use App\Listeners\RequestApprovedListener;
use App\Listeners\RequestSubmitedListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // add your listeners (aka providers) here
            \SocialiteProviders\Azure\AzureExtendSocialite::class . '@handle',
        ],
        RequestSubmited::class => [
            RequestSubmitedListener::class,
        ],
        RequestApproved::class => [
            RequestApprovedListener::class,
        ],
        MakeOrderEvent::class => [
            MakeOrderListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
