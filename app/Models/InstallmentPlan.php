<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['invoice_id', 'installment_number', 'due_date', 'nominal', 'paid_amount', 'remaining_amount', 'status'])]
class InstallmentPlan extends Model
{
    use HasFactory, HasUuids;

    public const STATUS_PENDING = 'Pending';
    public const STATUS_PARTIALLY_PAID = 'Partially Paid';
    public const STATUS_PAID = 'Paid';
    public const STATUS_OVERDUE = 'Overdue';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'due_date' => 'date',
        'nominal' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
