<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ItemResource;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\StockMutation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $query = Item::query()->with('category');

        if ($request->filled('q')) {
            $q = (string) $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->string('category_id'));
        }

        $items = $query->orderBy('name')->paginate(request('per_page', 20))->withQueryString();

        return ItemResource::collection($items);
    }

    public function show(Item $item): ItemResource
    {
        return new ItemResource($item->load('category'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:items,sku'],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'item_create',
            'subject_type' => Item::class,
            'subject_id' => $item->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Barang berhasil dibuat.',
            'data' => new ItemResource($item->load('category')),
        ], 201);
    }

    public function update(Request $request, Item $item): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:255', 'unique:items,sku,'.$item->id],
            'category_id' => ['required', 'uuid', 'exists:categories,id'],
            'unit' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $validated['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($validated);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'item_update',
            'subject_type' => Item::class,
            'subject_id' => $item->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Barang berhasil diperbarui.',
            'data' => new ItemResource($item->load('category')),
        ]);
    }

    public function destroy(Request $request, Item $item): JsonResponse
    {
        if ((int) $item->stock > 0) {
            return response()->json([
                'message' => 'Barang tidak bisa dihapus karena stok masih ada. Keluarkan stok sampai 0 terlebih dahulu.',
            ], 422);
        }

        if ($item->image_path) {
            Storage::disk('public')->delete($item->image_path);
        }

        $itemId = $item->id;
        $item->delete();

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'item_delete',
            'subject_type' => Item::class,
            'subject_id' => $itemId,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return response()->json([
            'message' => 'Barang berhasil dihapus.',
        ]);
    }
}
