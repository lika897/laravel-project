<?php

use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin|manager'])->group(function (){
    Route::get('/', \App\Http\Controllers\Admin\DashboardController::class)->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoriesController::class)->except(['show']);

    Route::resource('products', \App\Http\Controllers\Admin\ProductsController::class)->except(['show']);
});
