@extends('layouts.app')

@section('content')
    <div class="container py-3">
        <h2 class="mb-4">Category: {{ $category->title }}</h2>
{{--        <a href="{{ route('categories.show', $category->slug) }}"--}}
{{--           class="btn btn-outline-primary category-btn">--}}
{{--            {{ $category->title }}--}}
{{--        </a>--}}

        @if($products->count())
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($products as $product)
                    <div class="col">
                        <div class="card h-100">
                            @if($product->thumbnailUrl)
                                <img src="{{ $product->thumbnailUrl }}" class="card-img-top" alt="{{ $product->title }}" style="height: 200px; object-fit: cover;">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->title }}</h5>
                                <p class="card-text">
                                    @if($product->discount > 0)
                                        <span class="text-muted text-decoration-line-through">${{ number_format($product->price, 2) }}</span>
                                        <span class="text-danger fw-bold ms-2">${{ number_format($product->price * (1 - $product->discount / 100), 2) }}</span>
                                    @else
                                        <span class="fw-bold">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </p>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>No products found in this category.</p>
        @endif
    </div>
@endsection
