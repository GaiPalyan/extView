<?php

namespace App\Providers;

use App\Repository\DBDomainRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repository\DBDomainRepository;

class DBDomainRepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(DBDomainRepositoryInterface::class, DBDomainRepository::class);
    }
}
