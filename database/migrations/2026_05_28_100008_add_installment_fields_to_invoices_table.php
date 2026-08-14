<?php

use App\Models\InstallmentPlan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('is_installment')->default(false)->after('remaining_amount');
            $table->unsignedInteger('installment_count')->nullable()->after('is_installment');
        });

        $invoices = DB::table('invoices')->select('id')->get();

        foreach ($invoices as $invoice) {
            $installmentCount = InstallmentPlan::where('invoice_id', $invoice->id)->count();

            DB::table('invoices')
                ->where('id', $invoice->id)
                ->update([
                    'is_installment' => $installmentCount > 0,
                    'installment_count' => $installmentCount > 0 ? $installmentCount : null,
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['is_installment', 'installment_count']);
        });
    }
};