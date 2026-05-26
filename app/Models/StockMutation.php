<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'item_id',
    'item_sku_snapshot',
    'item_name_snapshot',
    'item_unit_snapshot',
    'action',
    'stock_before',
    'stock_after',
    'delta',
    'user_id',
    'stock_transaction_id',
    'note',
])]
class StockMutation extends Model
{
    use HasFactory, HasUuids;

    public const ACTION_IN = 'Barang Masuk';
    public const ACTION_OUT = 'Barang Keluar';
    public const ACTION_UPDATE_ITEM = 'Update Barang';
    public const ACTION_DELETE_ITEM = 'Delete Barang';

    public $incrementing = false;

    protected $keyType = 'string';

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(StockTransaction::class, 'stock_transaction_id');
    }
}
