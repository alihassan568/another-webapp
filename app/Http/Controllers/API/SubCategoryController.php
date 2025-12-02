<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subCategory = SubCategory::create($validated);

        return $this->success($subCategory, 'Sub-category created successfully');
    }

    public function update(Request $request, $id)
    {
        $subCategory = SubCategory::find($id);
        if (!$subCategory) {
            return $this->error('Sub-category not found', 404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $subCategory->update($validated);

        return $this->success($subCategory, 'Sub-category updated successfully');
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::find($id);
    
        if (!$subCategory) {
            return $this->error('Sub-category not found', 404);
        }

        $category = Category::where('id','=',$subCategory->category_id)->first();

        if(!empty($category) && $category->type == 'default') {
           return $this->error('Sub Category can not be deleted', 403);
        }

        $subCategory->delete();

        return $this->success([], 'Sub-category deleted successfully');
    }
}
