<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoriesAndProducts extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->truncate();
        DB::table('products')->truncate();

        Storage::deleteDirectory('faker');

        Category::factory(2)->create();
        Category::factory(5)->hasProducts(rand(10, 25))->create();

        Category::factory(2)->withParent()->create();
        Category::factory(5)->withParent()->hasProducts(rand(10, 25))->create();

    }
}
