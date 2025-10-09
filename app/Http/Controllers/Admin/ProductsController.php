<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permissions\ProductEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateRequest;
use App\Http\Requests\Products\EditRequest;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductsRepository;
use App\Repositories\Contracts\ProductsRepositoryContract;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories'])->orderByDesc('id')->paginate(10);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(CreateRequest $request, ProductsRepositoryContract $repository)
    {

        if ($product = $repository->store($request)) {
            return redirect()->route('admin.products.index');
        }


        return redirect()->back()->withInput();


    }

    public function edit(Product $product)
    {
        $product->load(['categories', 'images']);
        $categories = Category::all();
        $productCategories = $product->categories->pluck('id')->toArray();
        return view('admin.products.edit', compact('product','categories', 'productCategories'));
    }

    public function update(Product $product, EditRequest $request, ProductsRepositoryContract $repository)
    {
        if ($repository->update($request, $product)) {
            return redirect()->route('admin.products.index');
        }


        return redirect()->back()->withInput();
    }

    public function destroy(Product $product)
    {
        try {
            $this->middleware('permission:'.ProductEnum::DELETE->value);

            $product->deleteOrFail();

            return redirect()->route('admin.products.index');
        } catch (\Throwable $throwable){
            logs()->error('[ProductsController::destroy] ' . $throwable->getMessage(), [
                'product_id' => $product->id,
                'user_id' => auth()->id(),
            ]);
            return redirect()->back();
        }
    }
}
