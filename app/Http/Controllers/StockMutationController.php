<?php

namespace App\Http\Controllers;

use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StockMutationController extends Controller
{
    public function index(Request $request): View
    {
        $query = StockMutation::query()
            ->with(['item.category', 'user', 'transaction'])
            ->whereIn('action', [StockMutation::ACTION_IN, StockMutation::ACTION_OUT]);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->string('item_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to'));
        }

        $mutations = $query->orderByDesc('created_at')->paginate(request('per_page', 20))->withQueryString();

        return view('mutations.index', [
            'mutations' => $mutations,
        ]);
    }

    public function show(StockMutation $mutation): View
    {
        return view('mutations.show', [
            'mutation' => $mutation->load(['item.category', 'user', 'transaction']),
        ]);
    }

    public function export(Request $request): StreamedResponse
    {
        $query = StockMutation::query()
            ->with(['item.category', 'user', 'transaction'])
            ->whereIn('action', [StockMutation::ACTION_IN, StockMutation::ACTION_OUT]);

        if ($request->filled('item_id')) {
            $query->where('item_id', $request->string('item_id'));
        }

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->string('to'));
        }

        $mutations = $query->orderByDesc('created_at')->get();

        $fileName = 'mutations_'.$request->user()->id.'_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($mutations) {
            echo "\xEF\xBB\xBF";

            $out = fopen('php://output', 'w');
            fputcsv($out, [
                'Waktu',
                'SKU',
                'Barang',
                'Kategori',
                'Aksi',
                'Before',
                'After',
                'Delta',
                'User',
                'Catatan',
                'No Transaksi',
            ]);

            foreach ($mutations as $m) {
                fputcsv($out, [
                    optional($m->created_at)->format('Y-m-d H:i:s'),
                    $m->item_sku_snapshot ?? $m->item?->sku,
                    $m->item_name_snapshot ?? $m->item?->name,
                    $m->item?->category?->name,
                    $m->action,
                    $m->stock_before,
                    $m->stock_after,
                    $m->delta,
                    $m->user?->name,
                    $m->note,
                    $m->transaction?->transaction_number,
                ]);
            }

            fclose($out);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
