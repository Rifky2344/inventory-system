<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Item;
use App\Models\StockMutation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ItemController extends Controller
{
    public function index(Request $request): View
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

        $items = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('items.index', [
            'items' => $items,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function show(Item $item): View
    {
        return view('items.show', [
            'item' => $item->load('category'),
        ]);
    }

    public function create(): View
    {
        return view('items.create', [
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function store(ItemStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->boolean('auto_sku')) {
            $category = !empty($data['category_id']) ? Category::find($data['category_id']) : null;
            $prefix = $category?->category_code ?: 'ITM';
            
            // Get latest SKU with this prefix to determine the next number
            $latest = Item::where('sku', 'like', $prefix . '-%')
                ->orderBy('sku', 'desc')
                ->first();
                
            $nextNumber = 1;
            if ($latest) {
                $parts = explode('-', $latest->sku);
                $lastNum = (int) end($parts);
                $nextNumber = $lastNum + 1;
            }
            $data['sku'] = $prefix . '-' . str_pad((string)$nextNumber, 3, '0', STR_PAD_LEFT);
        }

        unset($data['auto_sku']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item = Item::create($data);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'item_create',
            'subject_type' => Item::class,
            'subject_id' => $item->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil dibuat.');
    }

    public function edit(Item $item): View
    {
        return view('items.edit', [
            'item' => $item,
            'categories' => Category::query()->orderBy('name')->get(),
        ]);
    }

    public function update(ItemUpdateRequest $request, Item $item): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($item->image_path) {
                Storage::disk('public')->delete($item->image_path);
            }
            $data['image_path'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'item_update',
            'subject_type' => Item::class,
            'subject_id' => $item->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('items.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Request $request, Item $item): RedirectResponse
    {
        if ((int) $item->stock > 0) {
            return back()->withErrors([
                'item' => 'Barang tidak bisa dihapus karena stok masih ada. Silakan keluarkan stok sampai 0 terlebih dahulu.',
            ]);
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

        return redirect()->route('items.index')->with('success', 'Barang berhasil dihapus.');
    }
}
