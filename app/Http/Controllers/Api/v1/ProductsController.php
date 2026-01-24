<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateRequest;
use App\Http\Resources\v1\ProductResource;
use App\Models\Product;
use App\Repositories\Contracts\ProductsRepositoryContract;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("Products Resource", "APIs for managing products")]
class ProductsController extends Controller
{
    public function __construct(protected ProductsRepositoryContract $repository)
    {
        $this->authorizeResource(Product::class, 'product');
    }
    /**
     * Display a listing of the resource.
     */

    #[Group("Retrieving all products")]
    public function index()
    {
        return ProductResource::collection(
            Product::with(['images', 'categories', 'categories.parent'])->orderByDesc('id')->paginate()

        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        if ($product = $this->repository->store($request)){
            return new ProductResource($product);
        }
        return response

        ()->json([
            'status' => 'error',
            'data' => [
                'message' => 'Product could not be created.'
            ]
        ], 422);
    }

    /**
     * Display the specified resource.
     */
    #[Group("Retrieving the specific product")]
    #[UrlParam("id", "integer", "The ID of the product")]
    public function show(Product $product)
    {
        $product->load(['categories', 'images']);

        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
