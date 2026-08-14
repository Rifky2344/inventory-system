<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['invoice_number', 'type', 'customer_id', 'sales_id', 'invoice_date', 'due_date', 'subtotal', 'total_amount', 'paid_amount', 'remaining_amount', 'is_installment', 'installment_count', 'payment_status', 'status', 'note', 'processed_by_warehouse', 'created_by'])]
class Invoice extends Model
{
    use HasFactory, HasUuids;

    public const STATUS_UNPAID = 'Unpaid';
    public const STATUS_PARTIALLY_PAID = 'Partially Paid';
    public const STATUS_PAID = 'Paid';
    public const STATUS_OVERDUE = 'Overdue';
    public const STATUS_CANCELLED = 'Cancelled';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'is_installment' => 'boolean',
        'processed_by_warehouse' => 'boolean',
        'installment_count' => 'integer',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(InstallmentPlan::class);
    }

    public static function formatNumber(\DateTime $date, int $sequence): string
    {
        return sprintf('INV-%s-%04d', $date->format('Ymd'), $sequence);
    }
}
