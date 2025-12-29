@extends('layouts.app')

@section('content')
    <div class="album py-5 bg-body-tertiary">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h3>Thank you for order, see information below:</h3>
                </div>
                <hr class="mb-5">
            </div>
            <div class="row">
                <div class="col-12 col-md-6">
                    <h4 class="mb-3">User info:</h4>
                    <table class="table table-striped-columns">
                        <tbody>
                        <tr>
                            <td>Name</td>
                            <td>{{ $order->name }}</td>
                        </tr>
                        @if($$showDetails)
                            <tr>
                                <td>Surname</td>
                                <td>{{ $order->lastname }}</td>
                            </tr>
                            <tr>
                                <td>Email</td>
                                <td>{{ $order->email }}</td>
                            </tr>
                            <tr>
                                <td>Phone</td>
                                <td>{{ $order->phone }}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>{{ $order->address }}</td>
                            </tr>
                            <tr>
                                <td>City</td>
                                <td>{{ $order->city }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-12 col-md-6">
                    <h4 class="mb-3">Order info:</h4>
                    <table class="table table-striped-columns">
                        <tbody>
                        <tr>
                            <td>Total</td>
                            <td>{{ $order->total }} $ (Tax included)</td>
                        </tr>
                        <tr>
                            <td>Tax</td>
                            <td>{{ config('cart.tax') }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>{{ $order->status }}</td>
                        </tr>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <hr class="mb-5">
                <div class="col-12 text-center mb-5">
                    <h3>Order Products:</h3>
                </div>

            </div>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($order->products as $product)
                            <tr>
                                <td><img src="{{ $product->thumbnailUrl }}" alt="{{ $product->title }}" width="35"></td>
                                <td>
                                    <a href="{{ route('products.show', $product) }}">{{ $product->pivot->title }}</a>
                                </td>
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ $product->pivot->single_price }}</td>
                                <td>{{ route($product->pivot->quantity * $product->pivot->single_price), 2 }}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-12 d-flex align-items-center justify-content-center gap-4">
                    <a href="{{ route('home') }}" class="btn-outline-secondary">Back to main page</a>
                    @if($showDetails)
                        <a href="{{ route('order.invoice', $order->vendor_order_id) }}" class="btn btn-outline-info">Open an invoice</a>

                    @else

                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
