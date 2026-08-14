<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::query()
            ->with('parent')
            ->orderBy('name')
            ->paginate(request('per_page', 20))
            ->withQueryString();

        return CategoryResource::collection($categories);
    }

    public function show(Category $category): CategoryResource
    {
        return new CategoryResource($category->load('parent'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'uuid', 'exists:categories,id'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'category_create',
            'subject_type' => Category::class,
            'subject_id' => $category->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dibuat.',
            'data' => new CategoryResource($category->load('parent')),
        ], 201);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'uuid', 'exists:categories,id', 'not_in:'.$category->id],
            'description' => ['nullable', 'string'],
        ]);

        $category->update($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'category_update',
            'subject_type' => Category::class,
            'subject_id' => $category->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => new CategoryResource($category->load('parent')),
        ]);
    }

    public function destroy(Request $request, Category $category): JsonResponse
    {
        if ($category->children()->exists()) {
            return response()->json([
                'message' => 'Kategori tidak bisa dihapus karena masih memiliki sub-kategori.',
            ], 422);
        }

        if ($category->items()->exists()) {
            return response()->json([
                'message' => 'Kategori tidak bisa dihapus karena masih memiliki barang.',
            ], 422);
        }

        $categoryId = $category->id;
        $category->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'category_delete',
            'subject_type' => Category::class,
            'subject_id' => $categoryId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
