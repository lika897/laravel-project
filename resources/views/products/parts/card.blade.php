<div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
    <div class="card h-100 shadow-sm d-flex flex-column">
        <div style="overflow: hidden; border-radius: 1.25rem 1.25rem 0 0;">
            <img src="{{ $product->thumbnailUrl }}"
                 alt="{{ $product->title }}"
                 class="card-img-top"
                 style="object-fit: cover; height: 200px; width: 100%;">
        </div>
        <div class="card-body d-flex flex-column justify-content-between flex-grow-1">
            <h5 class="card-title text-graphite">{{ $product->title }}</h5>
            <p class="card-text">
{{--                <strong>Price:</strong> ${{ number_format($product->price, 2) }}--}}
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
        <div class="card-footer bg-white border-top-0 p-3">
            <a href="{{route('products.show', $product)}}" class="btn btn-primary w-100">View Details</a>
        </div>
    </div>
</div>
