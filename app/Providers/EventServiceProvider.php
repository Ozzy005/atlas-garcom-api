<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Complement;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\ComplementObserver;
use App\Observers\ProductObserver;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
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
    ];

    /**
     * The model observers for your application.
     *
     * @var array
     */
    protected $observers = [
        User::class => [UserObserver::class],
        Role::class => [RoleObserver::class],
        Category::class => [CategoryObserver::class],
        Product::class => [ProductObserver::class],
        Complement::class => [ComplementObserver::class],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
