@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="mb-3">Products</h3>
            </div>

            @forelse($products as $product)
                @include('products.parts.card', ['product' => $product])
            @empty
                <div class="col-12">
                    <p>No products found.</p>
                </div>
            @endforelse

{{--            <div class="col-12 d-flex justify-content-center mt-4">--}}
{{--                {{$products->links()}}--}}
{{--            </div>--}}
            <div class="col-12 d-flex justify-content-center mt-4">
                <div class="pagination-wrapper">
                    {{ $products->links('vendor.pagination.custom') }}

                </div>
            </div>


        </div>
    </div>
@endsection
