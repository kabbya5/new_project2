<?php

namespace App\Providers;

use App\Models\GameTransaction;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton('GameService', function ($app) {
            return new \App\Services\GameService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        GameTransaction::observe(TransactionObserver::class);
    }
}
