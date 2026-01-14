@extends('layouts.app')

@section('content')
    <div class="container py-5">
        {{-- Hero Section --}}
{{--        <section class="hero-section mb-5">--}}
{{--            <div class="row align-items-center">--}}
{{--                <div class="col-lg-6 mb-4 mb-lg-0">--}}
{{--                    <span class="badge bg-light text-dark px-3 py-2 mb-3">In today's competitive world!</span>--}}
{{--                    <h1 class="fw-bold display-5 mb-3">The Journey To<br>A Fulfilling Career</h1>--}}
{{--                    <p class="text-muted mb-4">--}}
{{--                        Discover opportunities that fit your passion and skills. Build your path toward professional growth and fulfillment.--}}
{{--                    </p>--}}
{{--                    <div class="d-flex gap-3">--}}
{{--                        <a href="#productsContainer" class="btn btn-primary px-4 py-2">Find Your Job</a>--}}
{{--                        <a href="#" class="btn btn-outline-dark px-4 py-2">Learn More</a>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="col-lg-6 text-center">--}}
{{--                    <img src="{{ asset('images/hero-image.png') }}" alt="Career" class="img-fluid rounded-4 shadow-lg">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </section>--}}

        {{-- Categories --}}
        <section class="mb-5 fade-up">
            <h3 class="fw-bold text-center mb-4">Browse by Category</h3>
            <div class="d-flex flex-wrap justify-content-center gap-3">
                @foreach($categories as $category)
                    <button class="btn btn-outline-primary category-btn px-4 py-2 fw-semibold" data-id="{{ $category->id }}">
                        {{ $category->title }}
                    </button>
                @endforeach
            </div>
        </section>

        {{-- Products --}}
        <section id="productsContainer" class="fade-up">
            <h3 class="fw-bold text-center mb-4">Featured Products</h3>
            <div class="row g-4">
                @foreach($products as $product)
                    @include('products.parts.card', ['product' => $product])
                @endforeach
            </div>
        </section>

        {{-- Statistics --}}
        <section class="mt-5 fade-up">
            <div class="row text-center g-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <h3>154K</h3>
                        <p>Assistant Jobs</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <h3>20K</h3>
                        <p>Free Courses</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <h3>154K</h3>
                        <p>Alumni Jobs</p>
                    </div>
                </div>
            </div>
        </section>

{{--        --}}{{-- Partner Logos --}}
{{--        <section class="mt-5 partners-logos fade-up">--}}
{{--            <img src="{{ asset('images/logo1.png') }}" alt="Logo">--}}
{{--            <img src="{{ asset('images/logo2.png') }}" alt="Logo">--}}
{{--            <img src="{{ asset('images/logo3.png') }}" alt="Logo">--}}
{{--            <img src="{{ asset('images/logo4.png') }}" alt="Logo">--}}
{{--        </section>--}}
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
                        const row = document.createElement('div');
                        row.classList.add('row', 'g-4');

                        if (!products.length) {
                            productsContainer.innerHTML = '<p class="text-center mt-4">No products found in this category.</p>';
                            return;
                        }

                        row.innerHTML = products.map(product => `
                    <div class="col-md-3">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="${product.thumbnail ? baseUrl + '/' + product.thumbnail : 'placeholder.jpg'}" class="card-img-top" alt="${product.title}">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title fw-semibold">${product.title}</h5>
                                <p class="card-text mt-auto">$${product.price.toFixed(2)}</p>
                                <a href="/products/${product.slug}" class="btn btn-primary btn-sm mt-2">View</a>
                            </div>
                        </div>
                    </div>
                `).join('');

                        productsContainer.innerHTML = '';
                        productsContainer.appendChild(row);
                    })
                    .catch(() => {
                        productsContainer.innerHTML = '<p class="text-center mt-4 text-danger">Error loading products.</p>';
                    });
            });
        });
    </script>
@endsection
