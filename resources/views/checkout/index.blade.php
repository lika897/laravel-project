{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--    <div class="container py-5">--}}
{{--        <h3 class="text-center">Checkout Page (Under Construction)</h3>--}}
{{--        <p class="text-center">The order checkout page will be here.</p>--}}
{{--    </div>--}}
{{--@endsection--}}
@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h3 class="text-center mb-5">Checkout</h3>

        <div class="row g-4">
            {{-- LEFT SIDE: Checkout form --}}
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="mb-4">Billing Details</h5>

                        <form id="checkout-form">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">First Name</label>
                                    <input type="text" name="first_name" class="form-control" placeholder="John" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" placeholder="Doe" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" placeholder="email@example.com" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="+1 234 567 89" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Address</label>
                                <input type="text" name="address" class="form-control" placeholder="Street address" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control" required>
                                </div>

                            </div>

                            <div class="mb-3">
                                <label class="form-label">Order Notes (optional)</label>
                                <textarea class="form-control" name="notes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDE: Order summary --}}
            <div class="col-lg-5">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="mb-4">Your Order</h5>

                        {{-- Cart items --}}
                        @foreach($cart as $item)
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="d-flex align-items-center">
                                    {{-- Product image --}}
                                    <img
                                        src="{{ asset($item['thumbnailUrl']) }}"
                                        alt="{{ $item['title'] }}"
                                        class="rounded me-3"
                                        style="width: 50px; height: 50px; object-fit: cover;"
                                    >

                                    {{-- Product info --}}
                                    <div>
                                        <strong>{{ $item['title'] }}</strong>
                                        <div class="text-muted small">
                                            Qty: {{ $item['quantity'] }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Price --}}
                                <span>${{ number_format($item['subTotal'], 2) }}</span>
                            </div>
                        @endforeach


                        <hr>

                        {{-- Totals --}}
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>
                            ${{ number_format($cart->sum(fn($i) => $i['subTotal']), 2) }}
                        </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax ({{ config('cart.tax') }}%)</span>
                            <span>
                            ${{ number_format($cart->sum(fn($i) => $i['subTotal']) * config('cart.tax') / 100, 2) }}
                        </span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold mb-4">
                            <span>Total</span>
                            <span>
                            ${{ number_format($cart->sum(fn($i) => $i['subTotal']) * (1 + config('cart.tax')/100), 2) }}
                        </span>
                        </div>

                        {{-- PayPal --}}
                        <div id="paypal-button-container">

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @include('payments.paypal')
    @vite(['resources/js/paypal.js'])
@endsection



