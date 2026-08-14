<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customers')) {
            Schema::create('customers', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('name');
                $table->text('address');
                $table->string('province');
                $table->string('city');
                $table->string('contact');
                $table->string('email')->nullable();
                $table->string('pic')->nullable();
                $table->text('note')->nullable();
                $table->timestamps();
                $table->index(['name']);
                $table->index(['province', 'city']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
