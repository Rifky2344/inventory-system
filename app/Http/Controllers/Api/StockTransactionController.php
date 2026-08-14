<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockTransactionResource;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\StockMutation;
use App\Models\StockTransaction;
use App\Services\StockService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StockTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = StockTransaction::query()->with(['item.category', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('from')) {
            $query->whereDate('transaction_date', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('transaction_date', '<=', $request->string('to'));
        }

        $transactions = $query
            ->orderByDesc('transaction_date')
            ->orderByDesc('created_at')
            ->paginate(request('per_page', 20))
            ->withQueryString();

        return StockTransactionResource::collection($transactions);
    }

    public function show(StockTransaction $transaction): StockTransactionResource
    {
        return new StockTransactionResource($transaction->load(['item.category', 'user']));
    }

    public function storeIn(Request $request, StockService $service): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'uuid', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096'],
        ]);

        $transaction = $service->stockIn(
            user: $request->user(),
            item: Item::query()->findOrFail($validated['item_id']),
            quantity: (int) $validated['quantity'],
            transactionDate: Carbon::parse($validated['transaction_date']),
            note: $validated['note'] ?? null,
            proof: $request->file('proof')
        );

        return response()->json([
            'message' => 'Transaksi barang masuk berhasil disimpan.',
            'data' => new StockTransactionResource($transaction->load(['item.category', 'user'])),
        ], 201);
    }

    public function storeOut(Request $request, StockService $service): JsonResponse
    {
        $validated = $request->validate([
            'item_id' => ['required', 'uuid', 'exists:items,id'],
            'quantity' => ['required', 'integer', 'min:1'],
            'transaction_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096'],
        ]);

        try {
            $transaction = $service->stockOut(
                user: $request->user(),
                item: Item::query()->findOrFail($validated['item_id']),
                quantity: (int) $validated['quantity'],
                transactionDate: Carbon::parse($validated['transaction_date']),
                note: $validated['note'] ?? null,
                proof: $request->file('proof')
            );

            return response()->json([
                'message' => 'Transaksi barang keluar berhasil disimpan.',
                'data' => new StockTransactionResource($transaction->load(['item.category', 'user'])),
            ], 201);
        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function update(Request $request, StockTransaction $transaction): JsonResponse
    {
        $validated = $request->validate([
            'transaction_date' => ['required', 'date'],
            'quantity' => ['nullable', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096'],
        ]);

        $userId = $request->user()->id;

        try {
            $updatedTransaction = DB::transaction(function () use ($request, $transaction, $validated, $userId) {
            /** @var StockTransaction $lockedTransaction */
            $lockedTransaction = StockTransaction::query()->whereKey($transaction->getKey())->lockForUpdate()->firstOrFail();

            $updateData = [
                'transaction_date' => $validated['transaction_date'],
                'note' => $validated['note'] ?? null,
            ];

            if ($request->hasFile('proof')) {
                $updateData['proof_path'] = $request->file('proof')->store('proofs', 'public');
            }

            $quantityChanged = array_key_exists('quantity', $validated)
                && (int) $validated['quantity'] !== (int) $lockedTransaction->quantity;

            if ($quantityChanged) {
                $newQuantity = (int) $validated['quantity'];
                $oldQuantity = (int) $lockedTransaction->quantity;
                $qtyDiff = $newQuantity - $oldQuantity;

                /** @var Item $lockedItem */
                $lockedItem = Item::query()->whereKey($lockedTransaction->item_id)->lockForUpdate()->firstOrFail();

                $stockBefore = (int) $lockedItem->stock;
                $stockDelta = $lockedTransaction->type === StockTransaction::TYPE_IN ? $qtyDiff : -$qtyDiff;
                $stockAfter = $stockBefore + $stockDelta;

                if ($stockAfter < 0) {
                    throw new \DomainException('Tidak bisa mengubah quantity karena akan menyebabkan stok negatif.');
                }

                $lockedItem->stock = $stockAfter;
                $lockedItem->save();

                $updateData['quantity'] = $newQuantity;

                StockMutation::create([
                    'item_id' => $lockedItem->id,
                    'item_sku_snapshot' => $lockedItem->sku,
                    'item_name_snapshot' => $lockedItem->name,
                    'item_unit_snapshot' => $lockedItem->unit,
                    'action' => $lockedTransaction->type === StockTransaction::TYPE_IN ? StockMutation::ACTION_IN : StockMutation::ACTION_OUT,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'delta' => $stockDelta,
                    'user_id' => $userId,
                    'stock_transaction_id' => $lockedTransaction->id,
                    'note' => 'Adjustment: update qty '.$oldQuantity.' → '.$newQuantity.' untuk '.$lockedTransaction->transaction_number.' (API)',
                ]);
            }

            $lockedTransaction->update($updateData);

            ActivityLog::create([
                'user_id' => $userId,
                'action' => 'transaction_update',
                'subject_type' => StockTransaction::class,
                'subject_id' => $lockedTransaction->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'meta' => [
                    'quantity_changed' => $quantityChanged,
                    'new_quantity' => $quantityChanged ? (int) $validated['quantity'] : null,
                ],
            ]);

            return $lockedTransaction;
            });
        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Transaksi berhasil diperbarui.',
            'data' => new StockTransactionResource($updatedTransaction->load(['item.category', 'user'])),
        ]);
    }

    public function destroy(Request $request, StockTransaction $transaction): JsonResponse
    {
        try {
            DB::transaction(function () use ($request, $transaction) {
                $lockedItem = Item::query()->whereKey($transaction->item_id)->lockForUpdate()->firstOrFail();
                $stockBefore = (int) $lockedItem->stock;

                $reversalDelta = $transaction->type === StockTransaction::TYPE_IN
                    ? -(int) $transaction->quantity
                    : (int) $transaction->quantity;

                $stockAfter = $stockBefore + $reversalDelta;

                if ($stockAfter < 0) {
                    throw new \DomainException('Tidak bisa menghapus transaksi karena akan menyebabkan stok negatif.');
                }

                $lockedItem->stock = $stockAfter;
                $lockedItem->save();

                StockMutation::create([
                    'item_id' => $lockedItem->id,
                    'item_sku_snapshot' => $lockedItem->sku,
                    'item_name_snapshot' => $lockedItem->name,
                    'item_unit_snapshot' => $lockedItem->unit,
                    'action' => $transaction->type === StockTransaction::TYPE_IN ? StockMutation::ACTION_IN : StockMutation::ACTION_OUT,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'delta' => $reversalDelta,
                    'user_id' => $request->user()->id,
                    'note' => 'Reversal: delete transaksi '.$transaction->transaction_number.' (API)',
                ]);

                $transactionId = $transaction->id;
                $transaction->delete();

                ActivityLog::create([
                    'user_id' => $request->user()->id,
                    'action' => 'transaction_delete',
                    'subject_type' => StockTransaction::class,
                    'subject_id' => $transactionId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);
            });

            return response()->json([
                'message' => 'Transaksi berhasil dihapus.',
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
