@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <p class="m-0">{{ __('Products') }}</p>
                        <a href="{{route('admin.products.create')}}" class="btn btn-outline-dark">Create</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-dark table-striped m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>Title</th>
                                    <th style="max-width: 20%; width: 100%;">Categories</th>
                                    <th>SKU</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>
{{--                                        <img src="{{ asset($product->thumbnail) }}" alt="{{$product->title}}" style="max-width: 75px; height: auto;">--}}

                                        <img src="{{ Storage::url($product->thumbnail) }}" alt="{{$product->title}}" style="max-width: 75px; height: auto;">
                                    </td>
                                    <td>{{ $product->title }}</td>
                                    <td>
                                        @include('admin.products.parts.category-badge', ['categories' => $product->categories])
                                    </td>
                                    <td>{{ $product->SKU }}</td>
                                    <td>{{ $product->price }}</td>
                                    <td>{{ $product->quantity }}</td>
                                    <td>
                                        <form action="{{route('admin.products.destroy', $product)}}" method="post" class="d-flex align-items-center justify-content-start gap-2">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{route('admin.products.edit', $product)}}" class="btn btn-outline-warning">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <button type="submit" class="btn btn-outline-danger"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        {{ $products->links() }}


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
