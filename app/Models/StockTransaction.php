<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

#[Fillable([
    'type',
    'transaction_number',
    'item_id',
    'item_sku_snapshot',
    'item_name_snapshot',
    'item_unit_snapshot',
    'quantity',
    'transaction_date',
    'note',
    'proof_path',
    'created_by',
])]
class StockTransaction extends Model
{
    use HasFactory, HasUuids;

    public const TYPE_IN = 'in';
    public const TYPE_OUT = 'out';

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'transaction_date' => 'date',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class)->withTrashed();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function formatNumber(string $type, Carbon $date, int $sequence): string
    {
        $prefix = $type === self::TYPE_IN ? 'IN' : 'OUT';
        return sprintf('%s-%s-%04d', $prefix, $date->format('Ymd'), $sequence);
    }
}
