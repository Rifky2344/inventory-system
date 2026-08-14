<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('invoice_id')->constrained('invoices')->cascadeOnDelete();
                $table->date('payment_date');
                $table->decimal('amount', 15, 2);
                $table->string('payment_method')->default('Cash');
                $table->unsignedInteger('installment_number')->nullable();
                $table->text('notes')->nullable();
                $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->index(['payment_date']);
                $table->index(['payment_method']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
