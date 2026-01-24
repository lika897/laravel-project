<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card product-card h-100 shadow-sm d-flex flex-column fade-up position-relative">


        @if(session('success') && session('product_added_id') == $product->id)
            <div class="alert alert-success product-alert position-absolute top-0 start-50 translate-middle-x mt-2">
                {{ session('success') }}
            </div>
        @endif

        <!-- Изображение -->
        <div class="product-img-wrapper">
            <img src="{{ $product->thumbnailUrl }}"
                 alt="{{ $product->title }}"
                 class="card-img-top product-img">
            @if($product->discount > 0)
                <span class="badge bg-danger position-absolute top-0 start-0 m-2">
                    -{{ $product->discount }}%
                </span>
            @endif
        </div>

        <!-- Контент карточки -->
        <div class="card-body d-flex flex-column justify-content-between flex-grow-1">
            <h5 class="card-title text-graphite">{{ $product->title }}</h5>
            <p class="card-text">
                <strong>Price:</strong>
                @if($product->discount > 0)
                    <span class="fw-bold text-danger">
                        ${{ number_format($product->finalPrice, 2) }}
                    </span>
                    <span class="text-muted text-decoration-line-through ms-1">
                        ${{ number_format($product->price, 2) }}
                    </span>
                @else
                    <span class="fw-bold">
                        ${{ number_format($product->price, 2) }}
                    </span>
                @endif
            </p>
        </div>


        <form method="post" action="{{ route('cart.add', $product) }}" class="card-footer d-flex gap-2 bg-white border-top-0 p-3">
            @csrf
            <a href="{{ route('products.show', $product) }}" class="btn btn-primary flex-grow-1 btn-hover-scale">View Details</a>
            <button type="submit" class="btn btn-outline-dark flex-grow-1 btn-hover-scale">Buy</button>
        </form>
    </div>
</div>
