<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->string('item_sku_snapshot')->nullable()->after('item_id');
            $table->string('item_name_snapshot')->nullable()->after('item_sku_snapshot');
            $table->string('item_unit_snapshot')->nullable()->after('item_name_snapshot');
        });

        Schema::table('stock_mutations', function (Blueprint $table) {
            $table->string('item_sku_snapshot')->nullable()->after('item_id');
            $table->string('item_name_snapshot')->nullable()->after('item_sku_snapshot');
            $table->string('item_unit_snapshot')->nullable()->after('item_name_snapshot');
        });
    }

    public function down(): void
    {
        Schema::table('stock_transactions', function (Blueprint $table) {
            $table->dropColumn(['item_sku_snapshot', 'item_name_snapshot', 'item_unit_snapshot']);
        });

        Schema::table('stock_mutations', function (Blueprint $table) {
            $table->dropColumn(['item_sku_snapshot', 'item_name_snapshot', 'item_unit_snapshot']);
        });
    }
};
