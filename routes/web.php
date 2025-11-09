<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoriesController;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Products
Route::get('products', [\App\Http\Controllers\ProductsController::class, 'index'])->name('products.index');
Route::get('products/{product:slug}', [\App\Http\Controllers\ProductsController::class,'show'])->name('products.show');

//Categories
Route::get('categories/{category}/products', [CategoriesController::class, 'productsByCategory'])
    ->name('categories.products');
Route::get('categories/{category}', [CategoriesController::class, 'show'])->name('categories.show');
Route::get('/categories/{category:slug}', [CategoriesController::class, 'show'])
    ->name('categories.show');


Route::get('categories', [\App\Http\Controllers\CategoriesController::class, 'index'])->name('categories.index');

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');

    Route::post('add/{product}', [CartController::class, 'add'])->name('add');

    Route::patch('{uuid}', [CartController::class, 'update'])->name('update');
    Route::delete('{uuid}', [CartController::class, 'remove'])->name('remove');
});


Route::delete('clear', [\App\Http\Controllers\CartController::class, 'clear'])->name('clear');


Route::get('/checkout', function() {
    return view('checkout.index');
})->name('checkout.index');




//Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|manager'])->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);

    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->except(['show']);

});

Route::prefix('ajax')->name('ajax.')
    ->group(function (){
        Route::delete('images/{image}', \App\Http\Controllers\Ajax\RemoveImageControlle::class)
            ->middleware(['auth', 'role:admin|manager'])
            ->name('images.destroy');
    });


