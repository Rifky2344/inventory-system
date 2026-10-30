<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InstallmentPlan;
use App\Http\Requests\PaymentStoreRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['invoice', 'creator']);

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->whereHas('invoice', fn($invoiceQuery) => $invoiceQuery->where('invoice_number', 'like', "%{$q}%"));
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->get('method'));
        }

        $payments = $query->latest('payment_date')->paginate(15)->withQueryString();

        return view('payments.index', compact('payments'));
    }

    public function create(Request $request)
    {
        $invoice_id = $request->query('invoice_id');
        $invoice = null;
        $availableInstallments = collect();

        if ($invoice_id) {
            $invoice = Invoice::with(['customer', 'payments', 'installments'])->findOrFail($invoice_id);
            $availableInstallments = $invoice->installments()->orderBy('installment_number')->get();
        }

        $availableInvoices = Invoice::query()
            ->with('customer')
            ->whereIn('payment_status', [Invoice::STATUS_UNPAID, Invoice::STATUS_PARTIALLY_PAID, Invoice::STATUS_OVERDUE])
            ->orderByDesc('invoice_date')
            ->get();

        return view('payments.create', compact('invoice', 'availableInvoices', 'availableInstallments'));
    }

    public function store(PaymentStoreRequest $request)
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        $invoice = Invoice::findOrFail($data['invoice_id']);

        if ($invoice->is_installment) {
            $request->validate([
                'installment_number' => 'required|integer|min:1',
            ]);

            $installmentExists = InstallmentPlan::where('invoice_id', $invoice->id)
                ->where('installment_number', $data['installment_number'])
                ->exists();

            if (! $installmentExists) {
                return back()->withErrors(['installment_number' => 'Nomor cicilan tidak sesuai dengan invoice ini'])->withInput();
            }
        } else {
            $data['installment_number'] = null;
        }

        // Validate payment amount
        if ($data['amount'] > $invoice->remaining_amount) {
            return back()->withErrors(['amount' => 'Jumlah pembayaran melebihi sisa utang']);
        }

        if ($request->hasFile('payment_proof')) {
            $data['payment_proof_path'] = $request->file('payment_proof')->store('payments', 'public');
        }

        // Generate payment_number: [DDMMYYYY]-[Type]-[001]
        $dateStr = \Carbon\Carbon::parse($data['payment_date'])->format('dmY');
        $type = match ($data['payment_method']) {
            Payment::METHOD_TRANSFER => 'TF',
            Payment::METHOD_CASH => 'CS',
            Payment::METHOD_GIRO => 'GR',
            Payment::METHOD_LAINNYA => 'LL',
            default => 'XX',
        };

        $prefix = $dateStr . '-' . $type;
        $latestPayment = Payment::where('payment_number', 'like', $prefix . '-%')
            ->orderBy('payment_number', 'desc')
            ->first();

        $nextNum = 1;
        if ($latestPayment && $latestPayment->payment_number) {
            $parts = explode('-', $latestPayment->payment_number);
            $lastNum = (int) end($parts);
            $nextNum = $lastNum + 1;
        }

        $data['payment_number'] = $prefix . '-' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);

        // Create payment
        $payment = Payment::create($data);

        // If an installment number was provided, apply payment to that installment.
        // Otherwise, distribute the payment to the earliest unpaid installments.
        $amountToAllocate = $data['amount'];
        $firstApplied = null;

        if (!empty($data['installment_number'])) {
            $installment = InstallmentPlan::where('invoice_id', $invoice->id)
                ->where('installment_number', $data['installment_number'])
                ->first();

            if ($installment) {
                $applied = min($amountToAllocate, $installment->remaining_amount);
                $installment->paid_amount += $applied;
                $installment->remaining_amount -= $applied;

                if ($installment->remaining_amount <= 0) {
                    $installment->status = InstallmentPlan::STATUS_PAID;
                } else if ($installment->paid_amount > 0) {
                    $installment->status = InstallmentPlan::STATUS_PARTIALLY_PAID;
                }

                $installment->save();
                $amountToAllocate -= $applied;
                $firstApplied = $installment;
            }
        } else {
            $pending = InstallmentPlan::where('invoice_id', $invoice->id)
                ->where('remaining_amount', '>', 0)
                ->orderBy('installment_number')
                ->get();

            foreach ($pending as $inst) {
                if ($amountToAllocate <= 0) break;
                $applied = min($amountToAllocate, $inst->remaining_amount);
                $inst->paid_amount += $applied;
                $inst->remaining_amount -= $applied;

                if ($inst->remaining_amount <= 0) {
                    $inst->status = InstallmentPlan::STATUS_PAID;
                } else if ($inst->paid_amount > 0) {
                    $inst->status = InstallmentPlan::STATUS_PARTIALLY_PAID;
                }

                $inst->save();
                $amountToAllocate -= $applied;

                if (!$firstApplied) $firstApplied = $inst;
            }

            // If we applied to at least one installment, set the payment's installment_number to the first one applied
            if ($firstApplied) {
                $payment->installment_number = $firstApplied->installment_number;
                $payment->save();
            }
        }

        // Update invoice status
        $invoice->paid_amount += $data['amount'];
        $invoice->remaining_amount -= $data['amount'];

        if ($invoice->remaining_amount <= 0) {
            $invoice->payment_status = Invoice::STATUS_PAID;
        } else if ($invoice->paid_amount > 0) {
            $invoice->payment_status = Invoice::STATUS_PARTIALLY_PAID;
        }

        $invoice->save();

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'payment_input',
            'subject_type' => Invoice::class,
            'subject_id' => $invoice->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'meta' => [
                'payment_amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'invoice_number' => $invoice->invoice_number,
            ],
        ]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Pembayaran berhasil dicatat');
    }

    public function show(Payment $payment)
    {
        $payment->load(['invoice.customer', 'creator']);
        return view('payments.show', compact('payment'));
    }

    public function destroy(Payment $payment)
    {
        $invoice = $payment->invoice;

        // If this payment was applied to an installment, revert that installment too (best-effort)
        if (!empty($payment->installment_number)) {
            $installment = InstallmentPlan::where('invoice_id', $invoice->id)
                ->where('installment_number', $payment->installment_number)
                ->first();

            if ($installment) {
                $installment->paid_amount = max(0, (float) $installment->paid_amount - (float) $payment->amount);
                $installment->remaining_amount = max(0, (float) $installment->nominal - (float) $installment->paid_amount);

                if ($installment->paid_amount <= 0) {
                    $installment->status = InstallmentPlan::STATUS_PENDING;
                } elseif ($installment->remaining_amount <= 0) {
                    $installment->status = InstallmentPlan::STATUS_PAID;
                } else {
                    $installment->status = InstallmentPlan::STATUS_PARTIALLY_PAID;
                }

                $installment->save();
            }
        }

        $payment->delete();

        // Recalculate invoice amounts directly from actual remaining payments in database
        $totalPaid = (float) $invoice->payments()->sum('amount');
        $invoice->paid_amount = $totalPaid;
        $invoice->remaining_amount = max(0, (float) $invoice->total_amount - $totalPaid);

        if ($invoice->paid_amount <= 0) {
            $invoice->payment_status = Invoice::STATUS_UNPAID;
        } elseif ($invoice->remaining_amount <= 0) {
            $invoice->payment_status = Invoice::STATUS_PAID;
        } else {
            $invoice->payment_status = Invoice::STATUS_PARTIALLY_PAID;
        }

        $invoice->save();

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Pembayaran berhasil dihapus');
    }
}
