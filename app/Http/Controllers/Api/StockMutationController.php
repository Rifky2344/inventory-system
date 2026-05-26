<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockMutationResource;
use App\Models\StockMutation;
use Illuminate\Http\Request;

class StockMutationController extends Controller
{
    public function index(Request $request)
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

        $mutations = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return StockMutationResource::collection($mutations);
    }

    public function show(StockMutation $mutation): StockMutationResource
    {
        return new StockMutationResource($mutation->load(['item.category', 'user', 'transaction']));
    }
}
