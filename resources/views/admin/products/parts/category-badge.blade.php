@if($categories)
<div class="d-flex align-items-center justify-content-start gap-2 flex-wrap">
    @foreach($categories as $category)
    <a href="{{ route('admin.categories.edit', $category) }}" class="badge text-bg-light" style="text-decoration: none;"> {{ $category->title }}</a>
    @endforeach
</div>
@endif
