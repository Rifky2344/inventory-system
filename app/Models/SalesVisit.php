<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['sales_id', 'customer_id', 'visit_date', 'visit_result', 'note', 'follow_up_status'])]
class SalesVisit extends Model
{
    use HasFactory, HasUuids;

    public const FOLLOW_UP_PENDING = 'Pending';
    public const FOLLOW_UP_COMPLETED = 'Completed';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function sales(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sales_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
