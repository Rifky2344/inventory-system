<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('invoices')) {
            Schema::create('invoices', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('invoice_number')->unique();
                $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->foreignUuid('sales_id')->constrained('users')->cascadeOnDelete();
                $table->date('invoice_date');
                $table->date('due_date');
                $table->decimal('subtotal', 15, 2)->default(0);
                $table->decimal('total_amount', 15, 2)->default(0);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('remaining_amount', 15, 2)->default(0);
                $table->string('payment_status')->default('Unpaid');
                $table->text('note')->nullable();
                $table->foreignUuid('created_by')->constrained('users')->cascadeOnDelete();
                $table->timestamps();
                $table->index(['invoice_date', 'due_date']);
                $table->index(['payment_status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
