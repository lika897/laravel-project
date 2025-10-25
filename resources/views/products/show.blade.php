@extends('layouts.app')

@section('content')
    <div class="container my-2">
        <div class="row">

            <h2 class="product-title">{{ $product->title }}</h2>


            <div class="col-md-6">
                <div id="productGallery" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner rounded">
                        @foreach($gallery as $index => $imageUrl)
                            <div class="carousel-item @if($index === 0) active @endif">
                                <img src="{{ $imageUrl }}" class="d-block w-100 main-image" alt="Product Image {{ $index + 1 }}">
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

                        {{-- Thumbnails --}}
                        <div class="d-flex justify-content-center mt-3 gap-2 flex-wrap">
                            @foreach($gallery as $index => $imageUrl)
                                <img src="{{ $imageUrl }}"
                                     class="thumbnail-image rounded @if($index === 0) border border-primary @endif"
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


            <div class="col-md-6 d-flex flex-column justify-content-between">
                <div>

                    <p>
                        <strong>Categories: </strong>
                        @foreach($product->categories as $category)
                            <span class="badge bg-secondary">{{ $category->title }}</span>
                        @endforeach
                    </p>

                    <p><strong>SKU:</strong> {{ $product->SKU }}</p>
                    <p><strong>Quantity Available:</strong> {{ $product->quantity }}</p>

                    <p>{{ $product->description }}</p>

                    <div class="d-flex align-items-center justify-content-between p-3 border rounded shadow-sm w-100">
                        <div>
                            <strong>Price:</strong>
                            @if($product->discount > 0)
                                <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                <span class="text-danger fs-4 ms-2">${{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                                <span class="badge bg-danger ms-2">-{{ $product->discount }}%</span>
                            @else
                                <span class="fs-4">${{ number_format($product->price, 2) }}</span>
                            @endif
                        </div>
                        <button class="btn btn-primary w-auto px-4">Buy</button>
                    </div>





                </div>

                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mt-3 w-100">Back to Products</a>
            </div>

            <div class="col-12 p-3">
                <hr>
            </div>
            <div class="col-12">
                {{ $product->description }}
            </div>

        </div>
    </div>
@endsection
