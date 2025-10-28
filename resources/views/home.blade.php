@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h3 class="mb-3">Categories</h3>
                <div class="d-flex flex-wrap gap-2">
                    @foreach($categories as $category)
                        <button class="btn btn-outline-primary category-btn" data-id="{{ $category->id }}">
                            {{ $category->title }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row mt-4" id="productsContainer">
            <div class="col-12">
                <h3 class="mb-3">Products</h3>
            </div>

            @foreach($products as $product)
                @include('products.parts.card', ['product' => $product])
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const baseUrl = '{{ asset('storage') }}';
        const productsContainer = document.getElementById('productsContainer');

        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const categoryId = this.dataset.id;

                fetch(`/categories/${categoryId}/products`)
                    .then(res => res.json())
                    .then(products => {
                        if (!products.length) {
                            productsContainer.innerHTML = '<div class="col-12"><p>No products found in this category.</p></div>';
                            return;
                        }

                        productsContainer.innerHTML = products.map(product => `
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <img src="${product.thumbnail ? baseUrl + '/' + product.thumbnail : 'placeholder.jpg'}" class="card-img-top" alt="${product.title}">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">${product.title}</h5>
                                    <p class="card-text mt-auto">$${product.price.toFixed(2)}</p>
                                    <a href="/products/${product.slug}" class="btn btn-primary btn-sm mt-2">View</a>
                                </div>
                            </div>
                        </div>
                    `).join('');
                    })
                    .catch(err => {
                        console.error(err);
                        productsContainer.innerHTML = '<div class="col-12"><p>Error loading products.</p></div>';
                    });
            });
        });
    </script>
@endsection
