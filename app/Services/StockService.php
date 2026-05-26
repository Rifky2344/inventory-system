<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\Item;
use App\Models\StockMutation;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class StockService
{
    public function stockIn(
        User $user,
        Item $item,
        int $quantity,
        Carbon $transactionDate,
        ?string $note,
        ?UploadedFile $proof
    ): StockTransaction {
        return $this->createTransaction(
            type: StockTransaction::TYPE_IN,
            user: $user,
            item: $item,
            quantity: $quantity,
            transactionDate: $transactionDate,
            note: $note,
            proof: $proof
        );
    }

    public function stockOut(
        User $user,
        Item $item,
        int $quantity,
        Carbon $transactionDate,
        ?string $note,
        ?UploadedFile $proof
    ): StockTransaction {
        return $this->createTransaction(
            type: StockTransaction::TYPE_OUT,
            user: $user,
            item: $item,
            quantity: $quantity,
            transactionDate: $transactionDate,
            note: $note,
            proof: $proof
        );
    }

    private function createTransaction(
        string $type,
        User $user,
        Item $item,
        int $quantity,
        Carbon $transactionDate,
        ?string $note,
        ?UploadedFile $proof
    ): StockTransaction {
        return DB::transaction(function () use ($type, $user, $item, $quantity, $transactionDate, $note, $proof) {
            /** @var Item $lockedItem */
            $lockedItem = Item::query()->whereKey($item->getKey())->lockForUpdate()->firstOrFail();

            $stockBefore = (int) $lockedItem->stock;

            if ($quantity <= 0) {
                throw new \InvalidArgumentException('Quantity must be greater than 0.');
            }

            if ($type === StockTransaction::TYPE_OUT && $quantity > $stockBefore) {
                throw new \DomainException('Stok tidak mencukupi.');
            }

            $sequence = $this->nextDailySequence($type, $transactionDate);
            $transactionNumber = StockTransaction::formatNumber($type, $transactionDate, $sequence);

            $proofPath = $proof?->store('proofs', 'public');

            $transaction = StockTransaction::create([
                'type' => $type,
                'transaction_number' => $transactionNumber,
                'item_id' => $lockedItem->id,
                'item_sku_snapshot' => $lockedItem->sku,
                'item_name_snapshot' => $lockedItem->name,
                'item_unit_snapshot' => $lockedItem->unit,
                'quantity' => $quantity,
                'transaction_date' => $transactionDate->toDateString(),
                'note' => $note,
                'proof_path' => $proofPath,
                'created_by' => $user->id,
            ]);

            $stockAfter = $type === StockTransaction::TYPE_IN
                ? $stockBefore + $quantity
                : $stockBefore - $quantity;

            $lockedItem->stock = $stockAfter;
            $lockedItem->save();

            StockMutation::create([
                'item_id' => $lockedItem->id,
                'item_sku_snapshot' => $lockedItem->sku,
                'item_name_snapshot' => $lockedItem->name,
                'item_unit_snapshot' => $lockedItem->unit,
                'action' => $type === StockTransaction::TYPE_IN ? StockMutation::ACTION_IN : StockMutation::ACTION_OUT,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'delta' => $type === StockTransaction::TYPE_IN ? $quantity : -$quantity,
                'user_id' => $user->id,
                'stock_transaction_id' => $transaction->id,
                'note' => $note,
            ]);

            ActivityLog::create([
                'user_id' => $user->id,
                'action' => $type === StockTransaction::TYPE_IN ? 'stock_in' : 'stock_out',
                'subject_type' => StockTransaction::class,
                'subject_id' => $transaction->id,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'meta' => [
                    'transaction_number' => $transactionNumber,
                    'item_id' => $lockedItem->id,
                    'quantity' => $quantity,
                ],
            ]);

            return $transaction;
        });
    }

    private function nextDailySequence(string $type, Carbon $date): int
    {
        $latest = StockTransaction::query()
            ->where('type', $type)
            ->whereDate('transaction_date', $date)
            ->orderByDesc('transaction_number')
            ->lockForUpdate()
            ->first();

        if (!$latest) {
            return 1;
        }

        $suffix = (int) substr((string) $latest->transaction_number, -4);
        return $suffix + 1;
    }
}
