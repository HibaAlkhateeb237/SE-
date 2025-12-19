<?php

namespace App\Providers;

use App\Http\Repositories\AccountRepository;
use App\Http\Repositories\AccountRepositoryInterface;
use App\Http\Repositories\TransactionRepository;
use App\Http\Repositories\TransactionRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(AccountRepositoryInterface::class, AccountRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
