<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class CategoryController extends Controller
{
    // List all categories
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search = $request->get('search');

        $query = Category::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }

        $categories = $query->orderBy('id', 'desc')->paginate($perPage);

        return response()->json($categories);
    }

    // Show a single category
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Store a new category
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        try {
            $category = Category::create($request->all());
            return response()->json([
                'message' => 'Category created successfully',
                'category' => $category,
            ]);
        } catch (QueryException $e) {
            return response()->json(['message' => 'Failed to create category.'], 500);
        }
    }

    // Update category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $id,
            'description' => 'sometimes|nullable|string|max:1000',
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category,
        ]);
    }

    // Delete category (also deletes products if foreign key is cascade)
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete(); // if products have foreign key cascade, they will be deleted

        return response()->json([
            'message' => 'Category deleted successfully',
        ]);
    }
}
