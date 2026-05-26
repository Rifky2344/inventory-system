<?php

namespace App\Http\Controllers;

use App\Http\Requests\StockTransactionStoreRequest;
use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\StockMutation;
use App\Models\StockTransaction;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockTransactionController extends Controller
{
    public function index(Request $request): View
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

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('transactions.index', [
            'transactions' => $transactions,
        ]);
    }

    public function show(StockTransaction $transaction): View
    {
        return view('transactions.show', [
            'transaction' => $transaction->load(['item.category', 'user']),
        ]);
    }

    public function export(Request $request): StreamedResponse
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
            ->get();

        $fileName = 'transactions_'.$request->user()->id.'_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($transactions) {
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'No Transaksi',
                'Jenis',
                'Tanggal',
                'SKU',
                'Barang',
                'Kategori',
                'Qty',
                'Input Oleh',
                'Catatan',
                'Bukti URL',
            ]);

            foreach ($transactions as $t) {
                $proofUrl = $t->proof_path ? asset('storage/'.$t->proof_path) : '';

                fputcsv($out, [
                    $t->transaction_number,
                    strtoupper((string) $t->type),
                    optional($t->transaction_date)->format('Y-m-d'),
                    $t->item_sku_snapshot ?? $t->item?->sku,
                    $t->item_name_snapshot ?? $t->item?->name,
                    $t->item?->category?->name,
                    $t->quantity,
                    $t->user?->name,
                    $t->note,
                    $proofUrl,
                ]);
            }

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function createIn(): View
    {
        return view('transactions.create-in', [
            'items' => Item::query()->orderBy('name')->get(),
        ]);
    }

    public function storeIn(StockTransactionStoreRequest $request, StockService $service): RedirectResponse
    {
        try {
            $service->stockIn(
                user: $request->user(),
                item: Item::query()->findOrFail($request->string('item_id')),
                quantity: (int) $request->integer('quantity'),
                transactionDate: Carbon::parse($request->string('transaction_date')),
                note: $request->input('note'),
                proof: $request->file('proof')
            );

            return redirect()->route('transactions.index')->with('success', 'Transaksi barang masuk berhasil disimpan.');
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function createOut(): View
    {
        return view('transactions.create-out', [
            'items' => Item::query()->orderBy('name')->get(),
        ]);
    }

    public function storeOut(StockTransactionStoreRequest $request, StockService $service): RedirectResponse
    {
        try {
            $service->stockOut(
                user: $request->user(),
                item: Item::query()->findOrFail($request->string('item_id')),
                quantity: (int) $request->integer('quantity'),
                transactionDate: Carbon::parse($request->string('transaction_date')),
                note: $request->input('note'),
                proof: $request->file('proof')
            );

            return redirect()->route('transactions.index')->with('success', 'Transaksi barang keluar berhasil disimpan.');
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function edit(StockTransaction $transaction): View
    {
        return view('transactions.edit', [
            'transaction' => $transaction->load(['item', 'user']),
        ]);
    }

    public function update(Request $request, StockTransaction $transaction): RedirectResponse
    {
        $validated = $request->validate([
            'transaction_date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'note' => ['nullable', 'string'],
            'proof' => ['nullable', 'file', 'max:4096'],
        ]);

        try {
            DB::transaction(function () use ($request, $transaction, $validated) {
                /** @var StockTransaction $lockedTransaction */
                $lockedTransaction = StockTransaction::query()->whereKey($transaction->getKey())->lockForUpdate()->firstOrFail();

                $updateData = [
                    'transaction_date' => $validated['transaction_date'],
                    'note' => $validated['note'] ?? null,
                ];

                if ($request->hasFile('proof')) {
                    $updateData['proof_path'] = $request->file('proof')->store('proofs', 'public');
                }

                $newQuantity = (int) $validated['quantity'];
                $oldQuantity = (int) $lockedTransaction->quantity;
                $quantityChanged = $newQuantity !== $oldQuantity;

                if ($quantityChanged) {
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
                        'user_id' => $request->user()->id,
                        'stock_transaction_id' => $lockedTransaction->id,
                        'note' => 'Adjustment: update qty '.$oldQuantity.' → '.$newQuantity.' untuk '.$lockedTransaction->transaction_number,
                    ]);
                }

                $lockedTransaction->update($updateData);

                ActivityLog::create([
                    'user_id' => $request->user()->id,
                    'action' => 'transaction_update',
                    'subject_type' => StockTransaction::class,
                    'subject_id' => $lockedTransaction->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'meta' => [
                        'quantity_changed' => $quantityChanged,
                        'new_quantity' => $quantityChanged ? $newQuantity : null,
                    ],
                ]);
            });

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
        } catch (\DomainException $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, StockTransaction $transaction): RedirectResponse
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
                    'note' => 'Reversal: delete transaksi '.$transaction->transaction_number,
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

            return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
        } catch (\DomainException $e) {
            return back()->withErrors(['transaction' => $e->getMessage()]);
        }
    }
}
