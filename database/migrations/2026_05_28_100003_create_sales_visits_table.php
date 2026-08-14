<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sales_visits')) {
            Schema::create('sales_visits', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->foreignUuid('sales_id')->constrained('users')->cascadeOnDelete();
                $table->foreignUuid('customer_id')->constrained('customers')->cascadeOnDelete();
                $table->date('visit_date');
                $table->string('visit_result')->nullable();
                $table->text('note')->nullable();
                $table->string('follow_up_status')->default('Pending');
                $table->timestamps();
                $table->index(['visit_date']);
                $table->index(['follow_up_status']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_visits');
    }
};
