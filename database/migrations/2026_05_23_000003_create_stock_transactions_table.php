<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type'); // in|out
            $table->string('transaction_number')->unique();
            $table->foreignUuid('item_id')->constrained('items');
            $table->unsignedInteger('quantity');
            $table->date('transaction_date');
            $table->text('note')->nullable();
            $table->string('proof_path')->nullable();
            $table->foreignUuid('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['type', 'transaction_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_transactions');
    }
};
