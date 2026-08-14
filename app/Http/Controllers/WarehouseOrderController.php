<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseOrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Invoice::with(['customer', 'items.item', 'sales'])
            ->whereIn('status', ['pending', 'processing'])
            ->latest('invoice_date')
            ->paginate(15);
            
        return view('warehouse.index', compact('orders'));
    }

    public function show(Invoice $warehouse_order)
    {
        $warehouse_order->load(['customer', 'items.item', 'sales']);
        return view('warehouse.show', compact('warehouse_order'));
    }

    public function process(Request $request, Invoice $warehouse_order, StockService $stockService)
    {
        $processedItemIds = $request->input('processed_items', []);

        try {
            DB::transaction(function () use ($warehouse_order, $processedItemIds, $stockService) {
                $hasUpdates = false;

                foreach ($warehouse_order->items as $item) {
                    // Check if this item is newly processed
                    if (in_array($item->id, $processedItemIds) && !$item->is_processed) {
                        $item->is_processed = true;
                        $item->save();
                        $hasUpdates = true;

                        // Deduct/add stock for this specific item
                        $note = "Proses otomatis dari Invoice: " . $warehouse_order->invoice_number;
                        if ($warehouse_order->type === 'out') {
                            $stockService->stockOut(
                                user: auth()->user(),
                                item: $item->item,
                                quantity: $item->qty,
                                transactionDate: now(),
                                note: $note,
                                proof: null
                            );
                        } else {
                            $stockService->stockIn(
                                user: auth()->user(),
                                item: $item->item,
                                quantity: $item->qty,
                                transactionDate: now(),
                                note: $note,
                                proof: null
                            );
                        }
                    }
                }

                if ($hasUpdates) {
                    $warehouse_order->status = 'processing';
                    
                    // Check if all items are processed
                    $allProcessed = $warehouse_order->items()->where('is_processed', false)->count() === 0;
                    if ($allProcessed) {
                        $warehouse_order->status = 'completed';
                        $warehouse_order->processed_by_warehouse = true;
                    }
                    
                    $warehouse_order->save();
                }
            });

            return back()->with('success', 'Pesanan berhasil diupdate.');
        } catch (\DomainException $e) {
            return back()->with('error', 'Gagal memproses: ' . $e->getMessage());
        }
    }
}
