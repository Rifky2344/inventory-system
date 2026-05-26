<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $categories = Category::query()
            ->with('parent')
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($query) use ($q) {
                    $query
                        ->where('name', 'like', "%{$q}%")
                        ->orWhere('description', 'like', "%{$q}%")
                        ->orWhereHas('parent', function ($query) use ($q) {
                            $query->where('name', 'like', "%{$q}%");
                        });
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('categories.index', [
            'categories' => $categories,
            'q' => $q,
        ]);
    }

    public function create(): View
    {
        return view('categories.create', [
            'parents' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(CategoryStoreRequest $request): RedirectResponse
    {
        $category = Category::create($request->validated());

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'category_create',
            'subject_type' => Category::class,
            'subject_id' => $category->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dibuat.');
    }

    public function edit(Category $category): View
    {
        return view('categories.edit', [
            'category' => $category,
            'parents' => Category::query()
                ->whereKeyNot($category->getKey())
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function update(CategoryUpdateRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'category_update',
            'subject_type' => Category::class,
            'subject_id' => $category->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        if ($category->children()->exists()) {
            return back()->withErrors([
                'category' => 'Kategori tidak bisa dihapus karena masih memiliki sub-kategori.',
            ]);
        }

        if ($category->items()->exists()) {
            return back()->withErrors([
                'category' => 'Kategori tidak bisa dihapus karena masih memiliki barang.',
            ]);
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

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
