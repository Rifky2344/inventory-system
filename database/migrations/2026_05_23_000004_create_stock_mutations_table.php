<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('item_id')->constrained('items');
            $table->string('action');
            $table->unsignedInteger('stock_before');
            $table->unsignedInteger('stock_after');
            $table->integer('delta');
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('stock_transaction_id')->nullable()->constrained('stock_transactions')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['item_id', 'created_at']);
            $table->index(['action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_mutations');
    }
};
