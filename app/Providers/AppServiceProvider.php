<?php

namespace App\Providers;



use App\Repositories\Contracts\ProductsRepositoryContract;
use App\Repositories\ProductsRepository;
use App\Services\CartService;
use App\Services\Contracts\CartContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\FileService;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductsRepositoryContract::class => ProductsRepository::class,
        FileServiceContract::class => FileService::class,
        CartContract::class => CartService::class,

    ];


    public function register(): void
    {
//        $this->app->bind(ProductsRepositoryContract::class, ProductsRepository::class);
        $this->app->bind(
            \App\Services\Contracts\FileServiceContract::class,
            \App\Services\FileService::class
        );
        $this->app->singleton('cart', function($app) {
            return new \App\Services\CartService();
});

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
    }
}
