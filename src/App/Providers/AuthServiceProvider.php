<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Policies\ApprovalFlowPolicy;
use App\Policies\ApprovalRequestPolicy;
use App\Policies\ApprovalUserPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CityPolicy;
use App\Policies\CurrenciesPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductCategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RequestPolicy;
use App\Policies\SupplierPolicy;
use App\Policies\UnitPolicy;
use Domain\Purchases\Models\ApprovalFlow;
use Domain\Purchases\Models\ApprovalRequest;
use Domain\Purchases\Models\ApprovalUser;
use Domain\Purchases\Models\Category;
use Domain\Purchases\Models\City;
use Domain\Purchases\Models\Currency;
use Domain\Purchases\Models\Order;
use Domain\Purchases\Models\Product;
use Domain\Purchases\Models\ProductCategory;
use Domain\Purchases\Models\Request;
use Domain\Purchases\Models\Supplier;
use Domain\Purchases\Models\Unit;
use Domain\Users\Models\Role;
use Domain\Users\Policies\RolePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Role::class => RolePolicy::class,
        //<<========== Purchase ===========>>//
        ApprovalFlow::class => ApprovalFlowPolicy::class,
        ApprovalRequest::class => ApprovalRequestPolicy::class,
        ApprovalUser::class => ApprovalUserPolicy::class,
        Category::class => CategoryPolicy::class,
        Order::class => OrderPolicy::class,
        Request::class => RequestPolicy::class,
        City::class => CityPolicy::class,
        Currency::class => CurrenciesPolicy::class,
        Supplier::class => SupplierPolicy::class,
        Product::class => ProductPolicy::class,
        ProductCategory::class => ProductCategoryPolicy::class,
        Unit::class => UnitPolicy::class,


    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super-admin')) {
                return true;
            }
        });
    }
}
