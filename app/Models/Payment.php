<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['payment_number', 'invoice_id', 'payment_date', 'amount', 'payment_method', 'installment_number', 'notes', 'payment_proof_path', 'created_by'])]
class Payment extends Model
{
    use HasFactory, HasUuids;

    public const METHOD_CASH = 'Cash';
    public const METHOD_TRANSFER = 'Transfer Bank';
    public const METHOD_GIRO = 'Giro';
    public const METHOD_LAINNYA = 'Lainnya';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
