<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Item;
use App\Models\StockTransaction;
use App\Models\StockMutation;
use App\Models\Customer;
use App\Models\SalesVisit;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\InstallmentPlan;
use App\Models\Attendance;

class InventoryExportController extends Controller
{
    private function streamExcel(Spreadsheet $spreadsheet, string $filename): StreamedResponse
    {
        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    public function items(Request $request)
    {
        $query = Item::query()->with('category');

        if ($request->filled('q')) {
            $q = (string) $request->string('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('sku', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->string('category_id'));
        }

        $items = $query->orderBy('name')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Nama Barang');
        $sheet->setCellValue('B1', 'SKU');
        $sheet->setCellValue('C1', 'Kategori');
        $sheet->setCellValue('D1', 'Stok');
        $sheet->setCellValue('E1', 'Satuan');

        $row = 2;
        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $item->name);
            $sheet->setCellValue('B' . $row, $item->sku);
            $sheet->setCellValue('C' . $row, $item->category?->name);
            $sheet->setCellValue('D' . $row, $item->stock);
            $sheet->setCellValue('E' . $row, $item->unit);
            $row++;
        }

        return $this->streamExcel($spreadsheet, 'items_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function transactions(Request $request)
    {
        $query = StockTransaction::query()->with(['item.category', 'user']);

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->filled('from')) {
            $query->whereDate('transaction_date', '>=', $request->string('from'));
        }

        if ($request->filled('to')) {
            $query->whereDate('transaction_date', '<=', $request->string('to'));
        }

        $transactions = $query->orderByDesc('transaction_date')->orderByDesc('created_at')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['No Transaksi', 'Jenis', 'Tanggal', 'SKU', 'Barang', 'Kategori', 'Qty', 'Input Oleh', 'Catatan', 'Bukti URL'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($transactions as $t) {
            $proofUrl = $t->proof_path ? asset('storage/'.$t->proof_path) : '';
            
            $sheet->setCellValue('A' . $rowNum, $t->transaction_number);
            $sheet->setCellValue('B' . $rowNum, strtoupper((string) $t->type));
            $sheet->setCellValue('C' . $rowNum, optional($t->transaction_date)->format('Y-m-d'));
            $sheet->setCellValue('D' . $rowNum, $t->item_sku_snapshot ?? $t->item?->sku);
            $sheet->setCellValue('E' . $rowNum, $t->item_name_snapshot ?? $t->item?->name);
            $sheet->setCellValue('F' . $rowNum, $t->item?->category?->name);
            $sheet->setCellValue('G' . $rowNum, $t->quantity);
            $sheet->setCellValue('H' . $rowNum, $t->user?->name);
            $sheet->setCellValue('I' . $rowNum, $t->note);
            $sheet->setCellValue('J' . $rowNum, $proofUrl);
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'transactions_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function mutations(Request $request)
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['Waktu', 'SKU', 'Barang', 'Kategori', 'Aksi', 'Before', 'After', 'Delta', 'User', 'Catatan', 'No Transaksi'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($mutations as $m) {
            $sheet->setCellValue('A' . $rowNum, optional($m->created_at)->format('Y-m-d H:i:s'));
            $sheet->setCellValue('B' . $rowNum, $m->item_sku_snapshot ?? $m->item?->sku);
            $sheet->setCellValue('C' . $rowNum, $m->item_name_snapshot ?? $m->item?->name);
            $sheet->setCellValue('D' . $rowNum, $m->item?->category?->name);
            $sheet->setCellValue('E' . $rowNum, $m->action);
            $sheet->setCellValue('F' . $rowNum, $m->stock_before);
            $sheet->setCellValue('G' . $rowNum, $m->stock_after);
            $sheet->setCellValue('H' . $rowNum, $m->delta);
            $sheet->setCellValue('I' . $rowNum, $m->user?->name);
            $sheet->setCellValue('J' . $rowNum, $m->note);
            $sheet->setCellValue('K' . $rowNum, $m->transaction?->transaction_number);
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'mutations_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function customers(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('contact', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        if ($request->filled('province')) {
            $query->where('province', $request->get('province'));
        }

        $customers = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['Nama Customer', 'Kontak', 'Email', 'Alamat', 'Provinsi', 'Kota'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($customers as $c) {
            $sheet->setCellValue('A' . $rowNum, $c->name);
            $sheet->setCellValue('B' . $rowNum, $c->contact);
            $sheet->setCellValue('C' . $rowNum, $c->email);
            $sheet->setCellValue('D' . $rowNum, $c->address);
            $sheet->setCellValue('E' . $rowNum, $c->province);
            $sheet->setCellValue('F' . $rowNum, $c->city);
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'customers_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function salesVisits(Request $request)
    {
        $user = auth()->user();
        $query = SalesVisit::with(['customer', 'sales']);

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

        $visits = $query->latest('visit_date')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['Tanggal Visit', 'Sales', 'Customer', 'Hasil Visit', 'Catatan', 'Status Follow Up'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($visits as $v) {
            $sheet->setCellValue('A' . $rowNum, optional($v->visit_date)->format('Y-m-d'));
            $sheet->setCellValue('B' . $rowNum, $v->sales?->name);
            $sheet->setCellValue('C' . $rowNum, $v->customer?->name);
            $sheet->setCellValue('D' . $rowNum, $v->visit_result);
            $sheet->setCellValue('E' . $rowNum, $v->note);
            $sheet->setCellValue('F' . $rowNum, $v->follow_up_status);
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'sales_visits_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function invoices(Request $request)
    {
        $user = auth()->user();
        $query = Invoice::with(['customer', 'sales', 'creator']);

        if ($user->role->slug === 'sales') {
            $query->where('sales_id', $user->id);
        }

        if ($request->filled('q')) {
            $search = $request->get('q');
            $query->where(function ($q2) use ($search) {
                $q2->where('invoice_number', 'like', "%{$search}%")
                   ->orWhereHas('customer', fn($customerQuery) => $customerQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->get('status'));
        }

        $invoices = $query->latest('invoice_date')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['No Invoice', 'Tanggal', 'Jatuh Tempo', 'Customer', 'Sales', 'Total Tagihan', 'Terbayar', 'Sisa Tagihan', 'Status Pembayaran', 'Tipe Pembayaran'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($invoices as $inv) {
            $sheet->setCellValue('A' . $rowNum, $inv->invoice_number);
            $sheet->setCellValue('B' . $rowNum, optional($inv->invoice_date)->format('Y-m-d'));
            $sheet->setCellValue('C' . $rowNum, optional($inv->due_date)->format('Y-m-d'));
            $sheet->setCellValue('D' . $rowNum, $inv->customer?->name);
            $sheet->setCellValue('E' . $rowNum, $inv->sales?->name);
            $sheet->setCellValue('F' . $rowNum, $inv->total_amount);
            $sheet->setCellValue('G' . $rowNum, $inv->paid_amount);
            $sheet->setCellValue('H' . $rowNum, $inv->remaining_amount);
            $sheet->setCellValue('I' . $rowNum, statusLabel($inv->payment_status));
            $sheet->setCellValue('J' . $rowNum, $inv->is_installment ? 'Cicilan (' . $inv->installment_count . 'x)' : 'Cash / Sekali Bayar');
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'invoices_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function payments(Request $request)
    {
        $query = Payment::with(['invoice', 'creator']);

        if ($request->filled('q')) {
            $q = $request->get('q');
            $query->whereHas('invoice', fn($invoiceQuery) => $invoiceQuery->where('invoice_number', 'like', "%{$q}%"));
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->get('method'));
        }

        $payments = $query->latest('payment_date')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['Tanggal Pembayaran', 'No Invoice', 'Jumlah Dibayar', 'Metode Pembayaran', 'Referensi', 'Cicilan Ke', 'Diterima Oleh', 'Catatan'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($payments as $p) {
            $sheet->setCellValue('A' . $rowNum, optional($p->payment_date)->format('Y-m-d'));
            $sheet->setCellValue('B' . $rowNum, $p->invoice?->invoice_number);
            $sheet->setCellValue('C' . $rowNum, $p->amount);
            $sheet->setCellValue('D' . $rowNum, $p->payment_method);
            $sheet->setCellValue('E' . $rowNum, $p->reference_number);
            $sheet->setCellValue('F' . $rowNum, $p->installment_number ?? '-');
            $sheet->setCellValue('G' . $rowNum, $p->creator?->name);
            $sheet->setCellValue('H' . $rowNum, $p->notes);
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'payments_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function installments(Request $request)
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

        $invoices = $query->latest('created_at')->get();

        $today = now()->toDateString();
        $invoices->transform(function (Invoice $invoice) use ($today) {
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

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['No Invoice', 'Customer', 'Jumlah Cicilan', 'Total Tagihan', 'Sisa Tagihan', 'Status Cicilan', 'Jatuh Tempo Berikutnya'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($invoices as $inv) {
            $summary = $inv->installment_summary;
            $sheet->setCellValue('A' . $rowNum, $inv->invoice_number);
            $sheet->setCellValue('B' . $rowNum, $inv->customer?->name);
            $sheet->setCellValue('C' . $rowNum, $summary['installment_count'] . 'x');
            $sheet->setCellValue('D' . $rowNum, $summary['total_nominal']);
            $sheet->setCellValue('E' . $rowNum, $summary['remaining_total']);
            $sheet->setCellValue('F' . $rowNum, statusLabel($summary['status']));
            $sheet->setCellValue('G' . $rowNum, optional($summary['next_due_date'])->format('Y-m-d') ?? '-');
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'installments_' . now()->format('Ymd_His') . '.xlsx');
    }

    public function attendances(Request $request)
    {
        $user = auth()->user();
        if (in_array($user->role?->slug, ['admin', 'super-admin'])) {
            $attendances = Attendance::with('user.role')->orderBy('check_in_time', 'desc')->get();
        } else {
            $attendances = Attendance::with('user.role')->where('user_id', $user->id)->orderBy('check_in_time', 'desc')->get();
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $headers = ['Waktu Absen', 'Nama Pegawai', 'Role'];
        $colIndex = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($colIndex . '1', $header);
            $colIndex++;
        }

        $rowNum = 2;
        foreach ($attendances as $a) {
            $sheet->setCellValue('A' . $rowNum, optional($a->check_in_time)->format('Y-m-d H:i:s'));
            $sheet->setCellValue('B' . $rowNum, $a->user?->name);
            $sheet->setCellValue('C' . $rowNum, $a->user?->role?->name);
            $rowNum++;
        }

        return $this->streamExcel($spreadsheet, 'attendances_' . now()->format('Ymd_His') . '.xlsx');
    }
}
