<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('sku')->unique();
            $table->foreignUuid('category_id')->constrained('categories');
            $table->unsignedInteger('stock')->default(0);
            $table->string('unit');
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();

            $table->index(['name', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
