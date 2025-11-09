@extends('layouts.app')

@section('content')
    <div class="container py-3">

        <h2>Categories</h2>
        <div id="categoriesList" class="mb-4">
            @foreach($categories as $category)
                <button class="btn btn-outline-dark mb-1 category-btn" data-id="{{ $category->id }}" data-slug="{{ $category->slug }}">
                    {{ $category->title }}
                </button>
            @endforeach
        </div>

        <h3>Products</h3>
        <div id="productsList" class="row">
            <p class="col-12">Select a category to see products</p>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const baseUrl = '{{ asset('storage') }}';

        document.querySelectorAll('.category-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const categoryId = this.dataset.id;

                fetch(`/categories/${categoryId}/products`)
                    .then(res => {
                        if (!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    })
                    .then(products => {
                        const container = document.getElementById('productsList');
                        if (products.length === 0) {
                            container.innerHTML = '<p class="col-12">No products found in this category.</p>';
                            return;
                        }

                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                        container.innerHTML = products.map(product => `
                            <div class="col-md-3 mb-4">
                                <div class="card h-100">
                                    <img src="${product.thumbnail_url ? product.thumbnail_url : 'placeholder.jpg'}" class="card-img-top" alt="${product.title}">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">${product.title}</h5>
                                        <p class="card-text mb-2">$${product.price.toFixed(2)}</p>
                                        <a href="/products/${product.slug}" class="btn btn-primary mt-auto btn-sm mb-2">View</a>

                                        <form action="/cart/add/${product.slug}" method="POST" class="mt-auto">
                                            <input type="hidden" name="_token" value="${csrfToken}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-outline-dark flex-grow-1 btn-hover-scale">Buy</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            `).join('');



                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        document.getElementById('productsList').innerHTML = '<p class="col-12">Error loading products.</p>';
                    });
            });
        });
//Ajax
        document.addEventListener('click', function(e){
            if(e.target && e.target.matches('button.buy-btn')){
                e.preventDefault();
                const slug = e.target.dataset.slug;

                fetch(`/cart/add/${slug}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ quantity: 1 })
                })
                    .then(res => res.json())
                    .then(data => alert('Product added to cart!'))
                    .catch(err => console.error(err));
            }
        });



    </script>
@endsection
