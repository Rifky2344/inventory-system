<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'address', 'province', 'city', 'contact', 'email', 'pic', 'note'])]
class Customer extends Model
{
    use HasFactory, HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function visits(): HasMany
    {
        return $this->hasMany(SalesVisit::class);
    }
}
