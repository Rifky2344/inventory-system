<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'region')) {
                $table->string('region')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $cols = [];
            foreach (['phone', 'region', 'is_active'] as $c) {
                if (Schema::hasColumn('users', $c)) {
                    $cols[] = $c;
                }
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};
