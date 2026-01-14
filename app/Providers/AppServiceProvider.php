<?php

namespace App\Providers;



use App\Repositories\Contracts\ProductsRepositoryContract;
use App\Repositories\ProductsRepository;
use App\Services\CartService;
use App\Services\Contracts\CartContract;
use App\Services\Contracts\FileServiceContract;
use App\Services\Contracts\InvoiceServiceContract;
use App\Services\Contracts\PaypalServiceContract;
use App\Services\Contracts\ProductsExportServiceContract;
use App\Services\FileService;
use App\Services\ProductsExportService;
use App\Services\InvoiceService;
use App\Services\PaypalService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\OrderRepositoryContracts;
use App\Repositories\OrderRepository;



class AppServiceProvider extends ServiceProvider
{
    public $bindings = [
        ProductsRepositoryContract::class => ProductsRepository::class,
        FileServiceContract::class => FileService::class,
        CartContract::class => CartService::class,
        OrderRepositoryContracts::class => OrderRepository::class,
        PaypalServiceContract::class => PaypalService::class,
        InvoiceServiceContract::class => InvoiceService::class,
        ProductsExportServiceContract::class => ProductsExportService::class,


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
