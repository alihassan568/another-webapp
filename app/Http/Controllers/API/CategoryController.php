<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\SubCategory;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('subCategories')
            ->where('user_id', Auth::id())
            ->orWhere('type', '=','default')
            ->orderBy('id', 'desc')
            ->get();

        return $this->success($categories, 'Categories fetched successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sub_categories' => 'nullable|array',
            'sub_categories.*.name' => 'required|string|max:255',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'type' => 'user',
            'user_id' => Auth::id()
        ]);

        if (!empty($validated['sub_categories'])) {
            foreach ($validated['sub_categories'] as $sub) {
                $category->subCategories()->create(['name' => $sub['name']]);
            }
        }

        return $this->success($category->load('subCategories'), 'Category created');
    }

    public function show($id)
    {
        $category = Category::with('subCategories')->find($id);
        if (!$category) {
            return $this->error('Category not found', 404);
        }
        return $this->success($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('Category not found', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update(['name' => $validated['name']]);

        return $this->success($category, 'Category updated');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('Category not found', 404);
        }

        if ($category->type == 'default') {
            return $this->error('Category can not be deleted', 403);
        }

        SubCategory::where('category_id','=',$id)->delete();

        $category->delete();

        return $this->success([], 'Category deleted');
    }

    public function addSubCategory(Request $request, $categoryId)
    {
        $category = Category::find($categoryId);
        if (!$category) {
            return $this->error('Category not found', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $sub = $category->subCategories()->create(['name' => $validated['name']]);

        return $this->success($sub, 'Sub-category added');
    }
}
