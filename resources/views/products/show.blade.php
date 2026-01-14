@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row align-items-start g-5">


            <div class="col-12 text-center mb-4">
                <h1 class="fw-bold display-5 text-dark">{{ $product->title }}</h1>
                <p class="text-muted">Find everything you need for your next step forward</p>
            </div>


            <div class="col-md-6">
                <div id="productGallery" class="carousel slide product-carousel shadow-sm rounded-4 overflow-hidden" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($gallery as $index => $imageUrl)
                            <div class="carousel-item @if($index === 0) active @endif">
                                <img src="{{ $imageUrl }}" class="d-block w-100 product-main-img" alt="Product Image {{ $index + 1 }}">
                            </div>
                        @endforeach
                    </div>

                    @if(count($gallery) > 1)
                        <button class="carousel-control-prev custom-arrow" type="button" data-bs-target="#productGallery" data-bs-slide="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                        <button class="carousel-control-next custom-arrow" type="button" data-bs-target="#productGallery" data-bs-slide="next">
                            <i class="bi bi-chevron-right"></i>
                        </button>

                        <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                            @foreach($gallery as $index => $imageUrl)
                                <img src="{{ $imageUrl }}"
                                     class="thumbnail-image rounded-3 @if($index === 0) border border-primary @endif"
                                     style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                     data-bs-target="#productGallery"
                                     data-bs-slide-to="{{ $index }}"
                                     @if($index === 0) aria-current="true" @endif
                                     aria-label="Slide {{ $index + 1 }}">
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>


            <div class="col-md-6">
                <div class="product-details p-4 rounded-4 shadow-sm bg-white">
                    <div class="mb-3">
                        <p class="mb-2">
                            <strong>Categories:</strong>
                            @foreach($product->categories as $category)
                                <span class="badge rounded-pill bg-light text-dark border border-1 border-secondary">{{ $category->title }}</span>
                            @endforeach
                        </p>

                        <p class="mb-1"><strong>SKU:</strong> <span class="text-muted">{{ $product->SKU }}</span></p>
                        <p class="mb-3"><strong>In Stock:</strong> <span class="text-success fw-semibold">{{ $product->quantity }}</span></p>
                    </div>

{{--                    <p class="text-muted">{{ $product->description }}</p>--}}


                    <div class="d-flex align-items-center justify-content-between mt-4 p-3 rounded-3 border bg-light shadow-sm">
                        <div>
                            <strong class="text-dark">Price:</strong>
                            @if($product->discount > 0)
                                <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                <span class="text-primary fs-4 ms-2">${{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                                <span class="badge bg-danger ms-2">-{{ $product->discount }}%</span>
                            @else
                                <span class="fs-4 text-primary">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>


                        <form action="{{ route('cart.add', $product->slug) }}" method="POST">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-primary px-4 py-2 rounded-3">Buy Now</button>
                        </form>


                    </div>


                    <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100 mt-4">← Back to Products</a>
                </div>
            </div>


            <div class="col-12 mt-5">
                <hr class="mb-4">
                <h4 class="fw-bold text-dark mb-3">About this product</h4>
                <p class="text-muted">{{ $product->description }}</p>
            </div>

        </div>
    </div>
@endsection
