<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateRequest;
use App\Http\Resources\v2\ProductResource;
use App\Models\Product;
use App\Policies\Api\v2\ProductPolicy;
use App\Repositories\Contracts\ProductsRepositoryContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("Products Resource v2", "API v2 for managing products")]
class ProductsController extends Controller
{
    public function __construct(
        protected ProductsRepositoryContract $repository
    ) {
        $this->authorizeResource(Product::class, 'product',
            [
                'policy' => ProductPolicy::class
            ]);
    }

    /**
     * Display a listing of products.
     */
    #[Group("Retrieving all products v2")]
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 20);

        $products = Product::query()
            ->with(['images', 'categories'])
            ->latest()
            ->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'meta' => [
                'current_page' => $products->currentPage(),
                'total' => $products->total(),
            ],
            'data' => ProductResource::collection($products),
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(CreateRequest $request): JsonResponse
    {

        $this->authorize('create', Product::class);

        $product = $this->repository->store($request);

        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($product),
        ], 201);
    }

    /**
     * Display the specified product.
     */
    #[Group("Retrieving the specific product v2")]
    #[UrlParam("product", "integer", "The ID of the product")]
    public function show(Product $product): JsonResponse
    {
        $product->load(['categories', 'images']);

        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($product),
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $this->authorize('update', $product);

        $product->update($request->only([
            'title',
            'price',
            'description',
        ]));

        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($product),
        ]);
    }


    /**
     * Remove the specified product.
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorize('delete', $product);

        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted',
        ]);
    }
}
