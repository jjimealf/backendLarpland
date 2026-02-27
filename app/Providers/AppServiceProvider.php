<?php

namespace App\Providers;

use App\Models\Detail_Order;
use App\Models\Event_registration;
use App\Models\Order;
use App\Models\Product;
use App\Models\Product_Review;
use App\Models\Roleplay_event;
use App\Models\User;
use App\Policies\DetailOrderPolicy;
use App\Policies\EventRegistrationPolicy;
use App\Policies\OrderPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProductReviewPolicy;
use App\Policies\RoleplayEventPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Detail_Order::class, DetailOrderPolicy::class);
        Gate::policy(Product_Review::class, ProductReviewPolicy::class);
        Gate::policy(Roleplay_event::class, RoleplayEventPolicy::class);
        Gate::policy(Event_registration::class, EventRegistrationPolicy::class);
    }
}
