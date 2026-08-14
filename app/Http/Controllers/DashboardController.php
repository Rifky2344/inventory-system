<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\SalesVisit;
use App\Models\InstallmentPlan;
use App\Models\Customer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();
        $user = auth()->user();
        $role = $user->role?->slug;

        // Basic inventory stats (for all roles)
        $totalItems = Item::query()->count();
        $totalCategories = Category::query()->count();

        $inToday = StockTransaction::query()
            ->where('type', StockTransaction::TYPE_IN)
            ->whereDate('transaction_date', $today)
            ->count();

        $outToday = StockTransaction::query()
            ->where('type', StockTransaction::TYPE_OUT)
            ->whereDate('transaction_date', $today)
            ->count();

        $lowStockThreshold = (int) config('inventory.low_stock_threshold', 10);
        $lowStockItems = Item::query()
            ->where('stock', '<=', $lowStockThreshold)
            ->orderBy('stock')
            ->limit(10)
            ->get();

        $startOfWeek = $today->copy()->startOfWeek();
        $endOfWeek = $today->copy()->endOfWeek();

        $weekly = StockTransaction::query()
            ->selectRaw('transaction_date, type, COUNT(*) as total')
            ->whereBetween('transaction_date', [$startOfWeek, $endOfWeek])
            ->groupBy('transaction_date', 'type')
            ->orderBy('transaction_date')
            ->get();

        $data = [
            'totalItems' => $totalItems,
            'totalCategories' => $totalCategories,
            'inToday' => $inToday,
            'outToday' => $outToday,
            'lowStockThreshold' => $lowStockThreshold,
            'lowStockItems' => $lowStockItems,
            'weekly' => $weekly,
        ];

        // ERP Stats (for admin/super-admin)
        if (in_array($role, ['admin', 'super-admin'])) {
            // Invoice Summary
            $totalInvoices = Invoice::count();
            $unpaidInvoices = Invoice::where('payment_status', 'Unpaid')->count();
            $partiallyPaidInvoices = Invoice::where('payment_status', 'Partially Paid')->count();
            $paidInvoices = Invoice::where('payment_status', 'Paid')->count();
            $overdueInvoices = Invoice::where('payment_status', 'Overdue')->count();

            // Total amounts
            $totalSalesAmount = Invoice::sum('total_amount');
            $totalPaidAmount = Invoice::sum('paid_amount');
            $totalRemainingAmount = Invoice::sum('remaining_amount');

            // This month invoices
            $thisMonthInvoices = Invoice::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();
            $thisMonthSales = Invoice::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount');

            // Payment Summary
            $totalPayments = Payment::count();
            $thisMonthPayments = Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount');

            // Payment method breakdown
            $paymentsByMethod = Payment::select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(amount) as total'))
                ->groupBy('payment_method')
                ->get();

            // Overdue Installments
            $overdueInstallments = InstallmentPlan::where('status', '!=', 'Paid')
                ->where('due_date', '<', now()->toDateString())
                ->count();

            $overdueAmount = InstallmentPlan::where('status', '!=', 'Paid')
                ->where('due_date', '<', now()->toDateString())
                ->sum('remaining_amount');

            // Customer stats
            $totalCustomers = Customer::count();
            $customersWithInvoices = Invoice::distinct('customer_id')->count();

            // Sales activity
            $totalSalesVisits = SalesVisit::count();
            $thisMonthVisits = SalesVisit::whereMonth('visit_date', now()->month)
                ->whereYear('visit_date', now()->year)
                ->count();

            $pendingFollowUps = SalesVisit::where('follow_up_status', 'Pending')->count();

            // Most active sales
            $topSales = DB::table('users')
                ->join('invoices', 'users.id', '=', 'invoices.sales_id')
                ->select('users.id', 'users.name', DB::raw('COUNT(*) as invoice_count'), DB::raw('SUM(invoices.total_amount) as total_sales'))
                ->groupBy('users.id', 'users.name')
                ->orderByDesc('total_sales')
                ->limit(5)
                ->get();

            // Weekly sales trend (last 7 days)
            $weeklySales = Invoice::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as amount')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

            // Monthly sales trend (last 6 months)
            $monthlySales = Invoice::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as amount')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            $data = array_merge($data, [
                'totalInvoices' => $totalInvoices,
                'unpaidInvoices' => $unpaidInvoices,
                'partiallyPaidInvoices' => $partiallyPaidInvoices,
                'paidInvoices' => $paidInvoices,
                'overdueInvoices' => $overdueInvoices,
                'totalSalesAmount' => $totalSalesAmount,
                'totalPaidAmount' => $totalPaidAmount,
                'totalRemainingAmount' => $totalRemainingAmount,
                'thisMonthInvoices' => $thisMonthInvoices,
                'thisMonthSales' => $thisMonthSales,
                'totalPayments' => $totalPayments,
                'thisMonthPayments' => $thisMonthPayments,
                'paymentsByMethod' => $paymentsByMethod,
                'overdueInstallments' => $overdueInstallments,
                'overdueAmount' => $overdueAmount,
                'totalCustomers' => $totalCustomers,
                'customersWithInvoices' => $customersWithInvoices,
                'totalSalesVisits' => $totalSalesVisits,
                'thisMonthVisits' => $thisMonthVisits,
                'pendingFollowUps' => $pendingFollowUps,
                'topSales' => $topSales,
                'weeklySales' => $weeklySales,
                'monthlySales' => $monthlySales,
            ]);
        }

        // Sales user dashboard
        if ($role === 'sales') {
            $myVisits = SalesVisit::where('sales_id', $user->id)->count();
            $myInvoices = Invoice::where('sales_id', $user->id)->count();
            $myTotalSales = Invoice::where('sales_id', $user->id)->sum('total_amount');
            $myPendingFollowUps = SalesVisit::where('sales_id', $user->id)
                ->where('follow_up_status', 'Pending')
                ->count();

            $data = array_merge($data, [
                'myVisits' => $myVisits,
                'myInvoices' => $myInvoices,
                'myTotalSales' => $myTotalSales,
                'myPendingFollowUps' => $myPendingFollowUps,
            ]);
        }

        return view('dashboard', $data);
    }
}
