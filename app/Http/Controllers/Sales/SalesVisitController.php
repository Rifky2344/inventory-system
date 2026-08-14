<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\SalesVisit;
use App\Http\Requests\SalesVisitStoreRequest;
use App\Http\Requests\SalesVisitUpdateRequest;
use Illuminate\Http\Request;

class SalesVisitController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = SalesVisit::with(['customer', 'sales']);

        // Sales only see their own visits
        if ($user->role->slug === 'sales') {
            $query->where('sales_id', $user->id);
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->whereHas('customer', fn($customerQuery) => $customerQuery->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('follow_up_status', $request->get('status'));
        }

        $visits = $query->latest('visit_date')->paginate(15)->withQueryString();

        return view('sales-visits.index', compact('visits'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();

        return view('sales-visits.create', compact('customers'));
    }

    public function store(SalesVisitStoreRequest $request)
    {
        $data = $request->validated();
        $data['sales_id'] = auth()->id();

        SalesVisit::create($data);

        return redirect()->route('sales-visits.index')
            ->with('success', 'Visit customer berhasil dicatat');
    }

    public function show(SalesVisit $sales_visit)
    {
        $this->authorizeVisit($sales_visit);

        $visit = $sales_visit;
        $visit->load(['customer', 'sales']);

        // If relations are unexpectedly not loaded (some environment/middleware),
        // attempt explicit lookup to ensure the view has data.
        if (! $visit->relationLoaded('customer') || $visit->customer === null) {
            try {
                $visit->setRelation('customer', \App\Models\Customer::find($visit->customer_id));
            } catch (\Throwable $e) {
                // ignore
            }
        }

        if (! $visit->relationLoaded('sales') || $visit->sales === null) {
            try {
                $visit->setRelation('sales', \App\Models\User::find($visit->sales_id));
            } catch (\Throwable $e) {
                // ignore
            }
        }

        return view('sales-visits.show', compact('visit'));
    }

    public function edit(SalesVisit $sales_visit)
    {
        $this->authorizeVisit($sales_visit);
        $customers = Customer::orderBy('name')->get();

        $visit = $sales_visit;

        return view('sales-visits.edit', compact('visit', 'customers'));
    }

    public function update(SalesVisitUpdateRequest $request, SalesVisit $sales_visit)
    {
        $this->authorizeVisit($sales_visit);

        $sales_visit->update($request->validated());

        return redirect()->route('sales-visits.index')
            ->with('success', 'Visit berhasil diupdate');
    }

    public function destroy(SalesVisit $sales_visit)
    {
        $this->authorizeVisit($sales_visit);

        $sales_visit->delete();

        return redirect()->route('sales-visits.index')
            ->with('success', 'Visit berhasil dihapus');
    }

    private function authorizeVisit(SalesVisit $visit): void
    {
        $user = auth()->user();
        if ($user->role->slug === 'sales' && $visit->sales_id !== $user->id) {
            abort(403);
        }
    }
}
