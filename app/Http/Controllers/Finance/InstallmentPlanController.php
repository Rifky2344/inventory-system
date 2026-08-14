<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InstallmentPlan;
use Illuminate\Http\Request;

class InstallmentPlanController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()
            ->with([
                'customer',
                'installments' => fn($q) => $q->orderBy('installment_number'),
            ])
            ->where('is_installment', true)
            ->whereHas('installments');

        if ($request->filled('status')) {
            $status = $request->string('status')->toString();

            if ($status === InstallmentPlan::STATUS_OVERDUE) {
                $query->whereHas('installments', function ($q) {
                    $q->where('status', '!=', InstallmentPlan::STATUS_PAID)
                        ->where('due_date', '<', now()->toDateString());
                });
            } elseif ($status === InstallmentPlan::STATUS_PAID) {
                $query->whereDoesntHave('installments', function ($q) {
                    $q->where('status', '!=', InstallmentPlan::STATUS_PAID);
                });
            } elseif ($status === InstallmentPlan::STATUS_PARTIALLY_PAID) {
                $query->whereHas('installments', function ($q) {
                    $q->where('paid_amount', '>', 0);
                })->whereHas('installments', function ($q) {
                    $q->where('status', '!=', InstallmentPlan::STATUS_PAID);
                });
            } elseif ($status === InstallmentPlan::STATUS_PENDING) {
                $query->whereDoesntHave('installments', function ($q) {
                    $q->where('paid_amount', '>', 0);
                })->whereDoesntHave('installments', function ($q) {
                    $q->where('status', '!=', InstallmentPlan::STATUS_PAID)
                        ->where('due_date', '<', now()->toDateString());
                });
            }
        }

        $invoices = $query->latest('created_at')->paginate(15)->withQueryString();

        $today = now()->toDateString();
        $invoices->getCollection()->transform(function (Invoice $invoice) use ($today) {
            $installments = $invoice->installments;

            $totalNominal = (float) $installments->sum('nominal');
            $remainingTotal = (float) $installments->sum('remaining_amount');

            $hasOverdue = $installments->contains(function ($i) use ($today) {
                return $i->status !== InstallmentPlan::STATUS_PAID
                    && $i->due_date
                    && $i->due_date->toDateString() < $today;
            });

            $isAllPaid = $installments->every(fn($i) => $i->status === InstallmentPlan::STATUS_PAID);
            $hasAnyPaid = $installments->contains(fn($i) => (float) $i->paid_amount > 0);

            if ($hasOverdue) {
                $status = InstallmentPlan::STATUS_OVERDUE;
            } elseif ($isAllPaid) {
                $status = InstallmentPlan::STATUS_PAID;
            } elseif ($hasAnyPaid) {
                $status = InstallmentPlan::STATUS_PARTIALLY_PAID;
            } else {
                $status = InstallmentPlan::STATUS_PENDING;
            }

            $nextDue = $installments
                ->filter(fn($i) => $i->status !== InstallmentPlan::STATUS_PAID)
                ->sortBy('due_date')
                ->first()?->due_date;

            if (!$nextDue) {
                $nextDue = $installments->sortByDesc('due_date')->first()?->due_date;
            }

            $firstInstallment = $installments->sortBy('installment_number')->first();

            $invoice->installment_summary = [
                'installment_count' => $invoice->installment_count
                    ?? (int) ($installments->max('installment_number') ?? $installments->count()),
                'total_nominal' => $totalNominal,
                'remaining_total' => $remainingTotal,
                'next_due_date' => $nextDue,
                'status' => $status,
                'first_installment' => $firstInstallment,
            ];

            return $invoice;
        });

        // Calculate overdue installments
        $overdue_count = InstallmentPlan::where('status', '!=', InstallmentPlan::STATUS_PAID)
            ->whereHas('invoice', fn($invoiceQuery) => $invoiceQuery->where('is_installment', true))
            ->where('due_date', '<', now()->toDateString())
            ->count();

        return view('installment-plans.index', compact('invoices', 'overdue_count'));
    }

    public function show(\App\Models\InstallmentPlan $installmentPlan)
    {
        $invoice = $installmentPlan->invoice;
        if ($invoice) {
            $invoice->load(['customer', 'installments', 'payments']);
        }

        return view('installment-plans.show', compact('invoice'));
    }

    public function update(Request $request, \App\Models\InstallmentPlan $installmentPlan)
    {
        // This is for marking installments as paid
        $request->validate([
            'installment_id' => 'required|uuid|exists:installment_plans,id',
            'paid_amount' => 'required|numeric|min:0.01',
        ]);

        $installment = InstallmentPlan::findOrFail($request->installment_id);

        // Update installment
        $installment->paid_amount += $request->paid_amount;
        $installment->remaining_amount -= $request->paid_amount;

        if ($installment->remaining_amount <= 0) {
            $installment->status = InstallmentPlan::STATUS_PAID;
        } else if ($installment->paid_amount > 0) {
            $installment->status = InstallmentPlan::STATUS_PARTIALLY_PAID;
        }

        $installment->save();

        return back()->with('success', 'Cicilan berhasil diupdate');
    }
}
