@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center mb-4">
            <div class="col-12 text-center">
                <h3 class="fw-bold">Your Shopping Cart</h3>
                <hr class="w-25 mx-auto">
            </div>
        </div>

        <div class="row">
            @if($cart->isEmpty())
                <div class="col-12">
                    <div class="card text-center shadow-sm border-0">
                        <div class="card-body py-5">
                            <h3 class="mb-3">Your cart is empty</h3>
                            <a href="{{ route('products.index') }}" class="btn btn-lg btn-outline-primary">Go Shopping</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="col-12 col-lg-8">
                    @foreach($cart as $item)
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="row g-0 align-items-center">
                                @if(!empty($item['thumbnailUrl']))
                                    <div class="col-4 col-md-3">
                                        <img src="{{ $item['thumbnailUrl'] }}" class="img-fluid rounded-start" alt="{{ $item['title'] }}">
                                    </div>
                                @endif
                                <div class="col-8 col-md-6">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2">
                                            @if(!empty($item['slug']))
                                                <a href="{{ route('products.show', $item['slug']) }}" class="text-decoration-none">{{ $item['title'] }}</a>
                                            @else
                                                {{ $item['title'] }}
                                            @endif
                                        </h5>
                                        <p class="card-text mb-2">
                                            <strong>Price:</strong> ${{ number_format($item['price'], 2) }}
                                        </p>

                                        <form action="{{ route('cart.update', $item['uuid']) }}" method="POST" class="d-flex align-items-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control w-50">
                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="col-12 col-md-3 text-end pe-3">
                                    <form action="{{ route('cart.remove', $item['uuid']) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger mt-2">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-12 col-lg-4">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">Order Summary</h5>
                            <div class="mb-2 d-flex justify-content-between">
                                <span>Total Items:</span>
                                <span>{{ $cart->sum(fn($i) => $i['quantity']) }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span>Subtotal:</span>
                                <span>${{ number_format($cart->sum(fn($i) => $i['subTotal']), 2) }}</span>
                            </div>
                            <div class="mb-2 d-flex justify-content-between">
                                <span>Tax ({{ config('cart.tax') }}%):</span>
                                <span>${{ number_format($cart->sum(fn($i) => $i['subTotal']) * config('cart.tax') / 100, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold mb-3">
                                <span>Total:</span>
                                <span>${{ number_format($cart->sum(fn($i) => $i['subTotal']) * (1 + config('cart.tax')/100), 2) }}</span>
                            </div>

                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mb-2">Proceed to Checkout</a>
                            <a href="{{ route('clear') }}"
                               onclick="event.preventDefault(); document.getElementById('clear-cart-form').submit();"
                               class="btn btn-outline-secondary w-100">Clear Cart</a>

                            <form id="clear-cart-form" action="{{ route('clear') }}" method="POST" style="display:none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
