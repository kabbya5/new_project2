<?php

namespace App\Providers;

use App\Events\LikedEvent;
use Illuminate\Support\ServiceProvider;

class EventSErviceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        LikedEvent::class;
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
