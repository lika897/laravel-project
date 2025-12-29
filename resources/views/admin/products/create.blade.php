@extends('layouts.admin')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="post" class="card" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf

                    <div class="card-header">
                        {{ __('Create New Product') }}
                    </div>

                    <div class="card-body">

                        {{-- Title --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="title" id="title"
                                   class="form-control @error('title') is-invalid @enderror"
                                   value="{{ old('title') }}" placeholder="Product title" required>
                            <label for="title">Title</label>
                            @error('title')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- SKU --}}
                        <div class="form-floating mb-3">
                            <input type="text" name="SKU" id="SKU"
                                   class="form-control @error('SKU') is-invalid @enderror"
                                   value="{{ old('SKU') }}" placeholder="Stock keeping unit" required>
                            <label for="SKU">SKU</label>
                            @error('SKU')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Categories --}}
                        <div class="form-floating mb-3">
                            <select name="categories[]" id="categories"
                                    class="form-control @error('categories') is-invalid @enderror"
                                    multiple>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if(in_array($category->id, old('categories', []))) selected @endif>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Description --}}
                        <div class="form-floating mb-3">
                        <textarea name="description" id="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  placeholder="Product description" style="height: 120px;">{{ old('description') }}</textarea>
                            <label for="description">Description</label>
                            @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Price --}}
                        <div class="form-floating mb-3">
                            <input type="number" name="price" id="price" step="0.01" min="0"
                                   class="form-control @error('price') is-invalid @enderror"
                                   value="{{ old('price') }}" placeholder="Price" required>
                            <label for="price">Price</label>
                            @error('price')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Discount --}}
                        <div class="form-floating mb-3">
                            <input type="number" name="discount" id="discount" min="0" step="1" max="99"
                                   class="form-control @error('discount') is-invalid @enderror"
                                   value="{{ old('discount') }}" placeholder="Discount">
                            <label for="discount">Discount</label>
                            @error('discount')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Quantity --}}
                        <div class="form-floating mb-3">
                            <input type="number" name="quantity" id="quantity" min="0" step="1"
                                   class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity') }}" placeholder="Quantity" required>
                            <label for="quantity">Quantity</label>
                            @error('quantity')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

{{--                        --}}{{-- thumbnail --}}
{{--                        <div class="form-floating mb-3">--}}
{{--                            <input type="file" name="thumbnail" id="thumbnail"--}}
{{--                                   class="form-control @error('thumbnail') is-invalid @enderror"--}}
{{--                                   value="{{ old('thumbnail') }}" required>--}}
{{--                            <label for="thumbnail">Main image</label>--}}
{{--                            @error('thumbnail')--}}
{{--                            <span class="invalid-feedback">{{ $message }}</span>--}}
{{--                            @enderror--}}
{{--                        </div>--}}
                        {{-- thumbnail --}}
                        <div class="mb-3">
                            <label for="thumbnail" class="form-label">Main image</label>

                            <div class="mb-2">
                                <img id="thumbnail-preview"
                                     src="https://via.placeholder.com/150x150?text=Preview"
                                     alt="Image Preview"
                                     class="img-thumbnail"
                                     style="max-width: 150px;">
                            </div>

                            <input class="form-control @error('thumbnail') is-invalid @enderror"
                                   type="file"
                                   id="thumbnail"
                                   name="thumbnail"
                                   accept="image/*"
                                   required>

                            @error('thumbnail')
                            <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="images"
                                   class="form-label">{{ __('Additional Images') }}</label>

                            <div class="col-12 mb-2 d-flex align-items-center justify-content-center">
                                <div id="images-wrapper" class="row"></div>
                            </div>
                            <div class="col-md-12">
                                <input id="images" type="file"
                                       class="form-control @error('images') is-invalid @enderror" name="images[]" multiple>

                                @error('images')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>






                    </div>

                    <div class="card-footer d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-dark">Create</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>


        document.addEventListener('DOMContentLoaded', function () {
            initSelect2();
            initThumbnailPreview();
            initProductFormExtras();
        });
        function initThumbnailPreview() {
            const thumbnailInput = document.getElementById('thumbnail');
            const preview = document.getElementById('thumbnail-preview');

            if (!thumbnailInput || !preview) return;

            preview.style.display = 'none';

            thumbnailInput.addEventListener('change', function (event) {
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        preview.src = e.target.result;
                        preview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.src = '';
                    preview.style.display = 'none';
                }
            });
        }

        function initSelect2() {
            $('#categories').select2({
                placeholder: 'Select categories',
                width: '100%',
                allowClear: true
            });
        }

        function initProductFormExtras() {
            const imagesInput = document.getElementById('images');
            const previewWrapper = document.getElementById('images-wrapper');

            if (!imagesInput || !previewWrapper) return;

            imagesInput.addEventListener('change', function (event) {
                // Очистить старые превью
                previewWrapper.innerHTML = '';

                const files = Array.from(event.target.files);

                files.forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function (e) {
                            const col = document.createElement('div');
                            col.className = 'col-md-3 mb-3';

                            const img = document.createElement('img');
                            img.src = e.target.result;
                            img.className = 'img-fluid rounded border';
                            img.style.maxHeight = '120px';
                            img.alt = 'Preview';

                            col.appendChild(img);
                            previewWrapper.appendChild(col);
                        };

                        reader.readAsDataURL(file);
                    }
                });
            });
        }



    </script>

@endpush


{{--<script>--}}
{{--    document.addEventListener('DOMContentLoaded', function () {--}}
{{--        $('#categories').select2({--}}
{{--            placeholder: 'Select categories',--}}
{{--            width: '100%',--}}
{{--            allowClear: true--}}
{{--        });--}}
{{--    });--}}

{{--</script>--}}


