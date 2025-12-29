@extends('layouts.admin')

@php
$selectedItem = old('parent_id') ?? $category->parent_id;
@endphp

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" class="card" action="{{ route('admin.categories.update', $category) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-header">{{ __('Edit category') }}</div>
                    <div class="card-body">

                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Category name" name="title" value="{{ old('title') ?? $category->title }}">
                            <label for="title">Title</label>

                            @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <select name="parent_id" id="parent_id" class="form-control @error('parent_id') is-invalid @enderror" >
                                <option value="{{null}}" @unless(old('parent_id')) selected @endunless>No parent</option>
                                @foreach($categories as $item)
                                    <option
                                        value="{{ $item->id }}"
                                        @if($item->id == $selectedItem) selected @endif

                                    >{{ $item->title }}</option>
                                @endforeach
                            </select>
                            <label for="parent_id">Parent Category</label>
                            @error('parent_id')
                            <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                    </div>

                    <div class="card-footer d-flex align-items-center justify-content-end">
                        <button type="submit" class="btn btn-outline-dark">Update</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

@endsection
