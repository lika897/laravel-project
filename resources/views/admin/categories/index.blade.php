@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <p class="m-0">{{ __('Categories') }}</p>
                        <a href="{{route('admin.categories.create')}}" class="btn btn-outline-dark">Create</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-dark table-striped m-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Products count</th>
                                    <th>Parent</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>{{ $category->title }}</td>
                                    <td>{{ $category->products_count }}</td>
                                    <td>{{ $category?->parent?->title ?? '-' }}</td>
                                    <td>
                                        <form action="{{route('admin.categories.destroy', $category)}}" method="post" class="d-flex align-items-center justify-content-start gap-2">
                                            @csrf
                                            @method('DELETE')
                                            <a href="{{route('admin.categories.edit', $category)}}" class="btn btn-outline-warning">
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
                        {{ $categories->links() }}


                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
