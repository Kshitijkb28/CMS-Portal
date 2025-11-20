<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::orderBy('name')->get()
        );
    }

    public function store(CategoryRequest $request)
    {
        Gate::authorize('admin');

        $category = Category::create($request->validated());

        return CategoryResource::make($category);
    }

    public function show(Category $category)
    {
        return CategoryResource::make($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        Gate::authorize('admin');

        $category->update($request->validated());

        return CategoryResource::make($category);
    }

    public function destroy(Category $category)
    {
        Gate::authorize('admin');

        $category->delete();

        return response()->noContent();
    }
}
