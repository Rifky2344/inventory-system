<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\InstallmentPlan;
use App\Models\Invoice;
use App\Models\Item;
use App\Models\Customer;
use App\Http\Requests\InvoiceStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Invoice::with(['customer', 'sales', 'creator', 'items']);

        // Sales only see invoices they created
        if ($user->role->slug === 'sales') {
            $query->where('sales_id', $user->id);
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('customer', fn($customerQuery) => $customerQuery->where('name', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->get('status'));
        }

        $invoices = $query->latest('invoice_date')->paginate(15)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $customers = Customer::orderBy('name')->get();
        $items = Item::orderBy('name')->get();

        return view('invoices.create', compact('customers', 'items'));
    }

    public function store(InvoiceStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        // Ensure sales_id is not null because DB requires it.
        // If the form didn't provide a sales representative, default to the current user.
        if (empty($data['sales_id'])) {
            $data['sales_id'] = auth()->id();
        }

        // Generate invoice number
        if (!empty($data['is_auto_invoice'])) {
            $today = now()->format('Ymd');
            $count = Invoice::whereDate('created_at', now())->count() + 1;
            $data['invoice_number'] = Invoice::formatNumber(now(), $count);
        } else {
            $data['invoice_number'] = $data['invoice_number'];
        }

        // Calculate totals
        $items = $data['items'];
        unset($data['items']);
        unset($data['is_auto_invoice']);

        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['unit_price'] * $item['qty'];
        }

        $data['subtotal'] = $subtotal;
        $data['total_amount'] = $subtotal;
        $data['paid_amount'] = 0;
        $data['remaining_amount'] = $subtotal;
        $data['payment_status'] = Invoice::STATUS_UNPAID;
        $data['is_installment'] = false;
        $data['installment_count'] = 0;

        $invoice = DB::transaction(function () use ($data, $items, $request, $subtotal) {
            $invoice = Invoice::create($data);

            // Add items
            foreach ($items as $item) {
                $invoice->items()->create([
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $item['qty'] * $item['unit_price'],
                ]);
            }

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'invoice_create',
                'subject_type' => Invoice::class,
                'subject_id' => $invoice->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'meta' => [
                    'invoice_number' => $invoice->invoice_number,
                ],
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Invoice berhasil dibuat');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['customer', 'sales', 'creator', 'items.item', 'payments', 'installments']);

        return view('invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus');
    }
}
