<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('installment_plans')) {
            Schema::create('installment_plans', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('invoice_id')->constrained('invoices')->cascadeOnDelete();
                $table->unsignedInteger('installment_number');
                $table->date('due_date');
                $table->decimal('nominal', 15, 2);
                $table->decimal('paid_amount', 15, 2)->default(0);
                $table->decimal('remaining_amount', 15, 2);
                $table->string('status')->default('Pending');
                $table->timestamps();
                $table->unique(['invoice_id', 'installment_number']);
                $table->index(['due_date', 'status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
