<?php

namespace App\Providers;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Tool;
use App\Models\User;
use App\Observers\RecipeObserver;
use App\Observers\IngredientObserver;
use App\Observers\ToolObserver;
use App\Observers\UserObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        User::observe(UserObserver::class);
        Recipe::observe(RecipeObserver::class);
        Tool::observe(ToolObserver::class);
        Ingredient::observe(IngredientObserver::class);
    }
}
