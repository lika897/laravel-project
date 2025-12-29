<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriesController;

Route::get('/categories/{category}/products', [CategoriesController::class, 'productsByCategory']);
