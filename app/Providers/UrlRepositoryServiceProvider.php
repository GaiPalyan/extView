<?php

namespace App\Providers;

use App\Domain\UrlRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repository\UrlRepository;

class UrlRepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(UrlRepositoryInterface::class, UrlRepository::class);
    }
}
