<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Permissions\CategoryEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CreateRequest;
use App\Http\Requests\Categories\EditRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;


class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products'])
            ->with(['parent'])
            ->orderByDesc('id')
            ->paginate(7);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.create', compact('categories'));
    }

    public function store(CreateRequest $request)
    {
        try {
            Category::create([
                ...$request->validated(),
                'slug' => Str::slug($request->get('title')),

            ]);

            return redirect()->route('admin.categories.index');
        } catch (\Throwable $throwable){
            logs()->error('[CategoriesController::store] ' . $throwable->getMessage(), [
                'fields' => $request->validated(),
                'user_id' => auth()->id(),
            ]);
            return redirect()->back()->withInput();
        }
    }


    public function edit(Category $category)
    {
        $categories = Category::all();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(EditRequest $request, Category $category)
    {
        try {
            $category->updateOrFail([
                ...$request->validated(),
                'slug' => Str::slug($request->get('title')),

            ]);

            return redirect()->route('admin.categories.index');
        } catch (\Throwable $throwable){
            logs()->error('[CategoriesController::update] ' . $throwable->getMessage(), [
                'fields' => $request->validated(),
                'category_id' => $category->id,
                'user_id' => auth()->id(),
            ]);
            return redirect()->back()->withInput();
        }
    }

    public function destroy(Category $category)
    {
        try {
            $this->middleware('permission:'.CategoryEnum::DELETE->value);

            $category->deleteOrFail();

            return redirect()->route('admin.categories.index');
        } catch (\Throwable $throwable){
            logs()->error('[CategoriesController::destroy] ' . $throwable->getMessage(), [
                'category_id' => $category->id,
                'user_id' => auth()->id(),
            ]);
            return redirect()->back();
        }
    }
}
