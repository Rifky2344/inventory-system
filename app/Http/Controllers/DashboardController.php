<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\StockTransaction;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = Carbon::today();

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

        return view('dashboard', [
            'totalItems' => $totalItems,
            'totalCategories' => $totalCategories,
            'inToday' => $inToday,
            'outToday' => $outToday,
            'lowStockThreshold' => $lowStockThreshold,
            'lowStockItems' => $lowStockItems,
            'weekly' => $weekly,
        ]);
    }
}
