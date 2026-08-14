<?php

namespace Database\Seeders;

use App\Enums\RoleSlug;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Customer;
use App\Models\InstallmentPlan;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\Payment;
use App\Models\Role;
use App\Models\SalesVisit;
use App\Models\StockMutation;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // Avoid duplicating large demo data when seeding an existing DB.
        if (StockTransaction::query()->exists() || ActivityLog::query()->exists()) {
            return;
        }

        $roles = Role::query()
            ->whereIn('slug', [RoleSlug::SUPER_ADMIN->value, RoleSlug::ADMIN->value, RoleSlug::STAFF_GUDANG->value, RoleSlug::SALES->value])
            ->get()
            ->keyBy('slug');

        $superAdminRoleId = $roles[RoleSlug::SUPER_ADMIN->value]->id ?? null;
        $adminRoleId = $roles[RoleSlug::ADMIN->value]->id ?? null;
        $staffRoleId = $roles[RoleSlug::STAFF_GUDANG->value]->id ?? null;
        $salesRoleId = $roles[RoleSlug::SALES->value]->id ?? null;

        if (!$superAdminRoleId || !$adminRoleId || !$staffRoleId || !$salesRoleId) {
            return;
        }

        // Create known accounts
        $superAdmin = User::query()->firstOrCreate(
            ['email' => 'superadmin@demo.test'],
            ['name' => 'Super Admin Demo', 'password' => 'password', 'role_id' => $superAdminRoleId]
        );

        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@demo.test'],
            ['name' => 'Admin Demo', 'password' => 'password', 'role_id' => $adminRoleId]
        );

        $staff = User::query()->firstOrCreate(
            ['email' => 'staff@demo.test'],
            ['name' => 'Staff Gudang Demo', 'password' => 'password', 'role_id' => $staffRoleId]
        );

        // Additional users
        $admins = User::factory()->count(8)->create(['role_id' => $adminRoleId]);
        $staffs = User::factory()->count(30)->create(['role_id' => $staffRoleId]);
        
        $salesUsers = User::factory()
            ->count(10)
            ->state(fn () => [
                'role_id' => $salesRoleId,
                'phone' => fake()->phoneNumber(),
                'region' => fake()->randomElement(['Bandung', 'Jakarta', 'Bogor', 'Bekasi', 'Depok', 'Tangerang', 'Surabaya']),
                'is_active' => true,
            ])
            ->create();

        $actorPool = $admins->concat($staffs)->concat($salesUsers)->push($admin)->push($staff);

        // Ensure categories/items exist
        if (Category::query()->count() < 25) {
            $this->seedMoreCategories();
        }

        if (Item::query()->count() < 80) {
            $this->seedMoreItems(75);
        }

        // Stock transactions + mutations
        $items = Item::query()->inRandomOrder()->limit(80)->get();
        $startDate = now()->subDays(45);
        $sequenceByKey = [];

        for ($i = 0; $i < 250; $i++) {
            $item = $items->random();
            $type = fake()->randomElement([StockTransaction::TYPE_IN, StockTransaction::TYPE_OUT]);
            $date = Carbon::instance(fake()->dateTimeBetween($startDate, now()))->startOfDay();

            $maxOut = (int) $item->stock;
            if ($type === StockTransaction::TYPE_OUT && $maxOut <= 0) {
                $type = StockTransaction::TYPE_IN;
            }

            $qty = $type === StockTransaction::TYPE_OUT
                ? fake()->numberBetween(1, max(1, min(25, $maxOut)))
                : fake()->numberBetween(1, 40);

            $key = $type.'|'.$date->format('Ymd');
            $sequenceByKey[$key] = ($sequenceByKey[$key] ?? 0) + 1;
            $transactionNumber = StockTransaction::formatNumber($type, $date, $sequenceByKey[$key]);

            $actor = $actorPool->random();
            $stockBefore = (int) $item->stock;
            $delta = $type === StockTransaction::TYPE_IN ? $qty : -$qty;
            $stockAfter = max(0, $stockBefore + $delta);

            $transaction = StockTransaction::create([
                'type' => $type,
                'transaction_number' => $transactionNumber,
                'item_id' => $item->id,
                'item_sku_snapshot' => $item->sku,
                'item_name_snapshot' => $item->name,
                'item_unit_snapshot' => $item->unit,
                'quantity' => $qty,
                'transaction_date' => $date,
                'note' => fake()->boolean(25) ? fake()->sentence() : null,
                'proof_path' => null,
                'created_by' => $actor->id,
                'created_at' => Carbon::instance(fake()->dateTimeBetween($date, $date->copy()->endOfDay())),
            ]);

            $item->stock = $stockAfter;
            $item->save();

            StockMutation::create([
                'item_id' => $item->id,
                'item_sku_snapshot' => $item->sku,
                'item_name_snapshot' => $item->name,
                'item_unit_snapshot' => $item->unit,
                'action' => $type === StockTransaction::TYPE_IN ? StockMutation::ACTION_IN : StockMutation::ACTION_OUT,
                'stock_before' => $stockBefore,
                'stock_after' => $stockAfter,
                'delta' => $delta,
                'user_id' => $actor->id,
                'stock_transaction_id' => $transaction->id,
                'note' => $transaction->note,
                'created_at' => $transaction->created_at,
            ]);

            ActivityLog::create([
                'user_id' => $actor->id,
                'action' => $type === StockTransaction::TYPE_IN ? 'transaction_in_create' : 'transaction_out_create',
                'subject_type' => StockTransaction::class,
                'subject_id' => $transaction->id,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'meta' => ['transaction_number' => $transactionNumber, 'item_id' => $item->id, 'quantity' => $qty],
                'created_at' => $transaction->created_at,
            ]);
        }

        // Login events
        foreach ($actorPool->random(20) as $u) {
            ActivityLog::create([
                'user_id' => $u->id,
                'action' => 'auth_login',
                'subject_type' => User::class,
                'subject_id' => $u->id,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'meta' => null,
                'created_at' => now()->subDays(fake()->numberBetween(0, 15)),
            ]);
        }

        // ERP Data: Customers
        $customers = collect();
        for ($i = 0; $i < 60; $i++) {
            $customers->push(Customer::create([
                'name' => fake()->company(),
                'address' => fake()->address(),
                'province' => fake()->randomElement(['Jawa Barat', 'DKI Jakarta', 'Jawa Timur', 'Banten', 'Jawa Tengah']),
                'city' => fake()->city(),
                'contact' => fake()->phoneNumber(),
                'email' => fake()->boolean(65) ? fake()->unique()->safeEmail() : null,
                'pic' => fake()->boolean(60) ? fake()->name() : null,
                'note' => fake()->boolean(25) ? fake()->sentence() : null,
            ]));
        }

        // Sales Visits
        $visitResults = ['Ada PO', 'Bayar Tagihan', 'Follow Up', 'Tidak Bertemu', 'Customer Tutup', 'Penawaran Produk', 'Menunggu Keputusan'];
        $followUps = [SalesVisit::FOLLOW_UP_PENDING, SalesVisit::FOLLOW_UP_COMPLETED, 'Need Follow Up'];

        for ($i = 0; $i < 180; $i++) {
            $sales = $salesUsers->random();
            $customer = $customers->random();
            $visitDate = Carbon::instance(fake()->dateTimeBetween($startDate, now()))->startOfDay();

            $visit = SalesVisit::create([
                'sales_id' => $sales->id,
                'customer_id' => $customer->id,
                'visit_date' => $visitDate,
                'visit_result' => fake()->randomElement($visitResults),
                'note' => fake()->boolean(35) ? fake()->sentence() : null,
                'follow_up_status' => fake()->randomElement($followUps),
                'created_at' => Carbon::instance(fake()->dateTimeBetween($visitDate, $visitDate->copy()->endOfDay())),
            ]);

            ActivityLog::create([
                'user_id' => $sales->id,
                'action' => 'sales_visit_create',
                'subject_type' => SalesVisit::class,
                'subject_id' => $visit->id,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'meta' => ['customer_id' => $customer->id],
                'created_at' => $visit->created_at,
            ]);
        }

        // Invoices
        $invoiceStart = now()->subDays(35);
        $invoiceSequenceByDate = [];
        $availableItems = $items->random(60);

        for ($i = 0; $i < 90; $i++) {
            $invoiceDate = Carbon::instance(fake()->dateTimeBetween($invoiceStart, now()))->startOfDay();
            $key = $invoiceDate->format('Ymd');
            $invoiceSequenceByDate[$key] = ($invoiceSequenceByDate[$key] ?? 0) + 1;
            $invoiceNumber = Invoice::formatNumber($invoiceDate, $invoiceSequenceByDate[$key]);

            $customer = $customers->random();
            $sales = $salesUsers->random();
            $creator = $admins->random();
            $dueDate = $invoiceDate->copy()->addDays(fake()->numberBetween(7, 30));

            // Invoice items
            $lineCount = fake()->numberBetween(1, 4);
            $pickedItems = $availableItems->random($lineCount);
            $subtotal = 0;

            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'customer_id' => $customer->id,
                'sales_id' => $sales->id,
                'invoice_date' => $invoiceDate,
                'due_date' => $dueDate,
                'subtotal' => 0,
                'total_amount' => 0,
                'paid_amount' => 0,
                'remaining_amount' => 0,
                'is_installment' => false,
                'installment_count' => null,
                'payment_status' => Invoice::STATUS_UNPAID,
                'note' => fake()->boolean(25) ? fake()->sentence() : null,
                'created_by' => $creator->id,
                'created_at' => Carbon::instance(fake()->dateTimeBetween($invoiceDate, $invoiceDate->copy()->endOfDay())),
            ]);

            $totalAmount = 0;
            foreach ($pickedItems as $pickedItem) {
                $qty = fake()->numberBetween(1, 8);
                $unitPrice = fake()->numberBetween(15000, 250000);
                $lineSubtotal = $qty * $unitPrice;
                $totalAmount += $lineSubtotal;

                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'item_id' => $pickedItem->id,
                    'qty' => $qty,
                    'unit_price' => $unitPrice,
                    'subtotal' => $lineSubtotal,
                ]);
            }

            $invoice->subtotal = $totalAmount;
            $invoice->total_amount = $totalAmount;
            $invoice->remaining_amount = $totalAmount;
            $invoice->save();

            ActivityLog::create([
                'user_id' => $creator->id,
                'action' => 'invoice_create',
                'subject_type' => Invoice::class,
                'subject_id' => $invoice->id,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'meta' => ['invoice_number' => $invoiceNumber, 'total' => $totalAmount],
                'created_at' => $invoice->created_at,
            ]);

            // Installment plans (~35%)
            if (fake()->boolean(35)) {
                $installmentCount = fake()->randomElement([2, 3, 4]);
                $invoice->is_installment = true;
                $invoice->installment_count = $installmentCount;
                $invoice->save();

                $per = round($totalAmount / $installmentCount, 2);
                $sum = 0;

                for ($n = 1; $n <= $installmentCount; $n++) {
                    $nominal = $n === $installmentCount ? round($totalAmount - $sum, 2) : $per;
                    $sum += $nominal;

                    InstallmentPlan::create([
                        'invoice_id' => $invoice->id,
                        'installment_number' => $n,
                        'due_date' => $invoiceDate->copy()->addMonthsNoOverflow($n),
                        'nominal' => $nominal,
                        'paid_amount' => 0,
                        'remaining_amount' => $nominal,
                        'status' => InstallmentPlan::STATUS_PENDING,
                    ]);
                }
            }

            // Payments (~55%)
            if (fake()->boolean(55)) {
                $payCount = fake()->randomElement([1, 1, 2, 3]);
                $paidTotal = 0;

                for ($p = 1; $p <= $payCount; $p++) {
                    if ($paidTotal >= $totalAmount) break;
                    
                    $maxPayment = $totalAmount - $paidTotal;
                    $amount = (float) fake()->numberBetween(1, max(1, (int) $maxPayment));
                    $amount = min($amount, $maxPayment);

                    Payment::create([
                        'invoice_id' => $invoice->id,
                        'payment_date' => $invoiceDate->copy()->addDays(fake()->numberBetween(0, 20)),
                        'amount' => $amount,
                        'payment_method' => fake()->randomElement([Payment::METHOD_CASH, Payment::METHOD_TRANSFER, Payment::METHOD_GIRO]),
                        'installment_number' => $invoice->is_installment ? fake()->numberBetween(1, $invoice->installment_count) : null,
                        'notes' => fake()->boolean(20) ? fake()->sentence() : null,
                        'created_by' => $sales->id,
                    ]);

                    $paidTotal += $amount;
                }

                // Update invoice status
                if ($paidTotal <= 0) {
                    $status = Invoice::STATUS_UNPAID;
                } elseif ($paidTotal < $totalAmount) {
                    $status = Invoice::STATUS_PARTIALLY_PAID;
                } else {
                    $status = Invoice::STATUS_PAID;
                }

                if (now()->startOfDay()->greaterThan($dueDate) && $paidTotal < $totalAmount) {
                    $status = Invoice::STATUS_OVERDUE;
                }

                $invoice->paid_amount = $paidTotal;
                $invoice->remaining_amount = max(0, $totalAmount - $paidTotal);
                $invoice->payment_status = $status;
                $invoice->save();

                ActivityLog::create([
                    'user_id' => $sales->id,
                    'action' => 'payment_input',
                    'subject_type' => Invoice::class,
                    'subject_id' => $invoice->id,
                    'ip_address' => fake()->ipv4(),
                    'user_agent' => fake()->userAgent(),
                    'meta' => ['paid_amount' => $paidTotal, 'status' => $status],
                    'created_at' => now(),
                ]);
            }
        }

        // Admin logs
        ActivityLog::create([
            'user_id' => $superAdmin->id,
            'action' => 'user_create',
            'subject_type' => User::class,
            'subject_id' => $admin->id,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
            'meta' => ['seeded' => true],
        ]);
    }

    private function seedMoreCategories(): void
    {
        $root = Category::query()->firstOrCreate(
            ['name' => 'Pertanian', 'parent_id' => null],
            ['description' => 'Kategori utama pertanian (demo data).']
        );

        $parents = collect([
            ['Benih', 'Benih tanaman'],
            ['Pupuk', 'Pupuk organik & kimia'],
            ['Pestisida', 'Pengendalian hama'],
            ['Alat Tani', 'Peralatan pertanian'],
            ['Irigasi', 'Peralatan irigasi'],
            ['Pakan', 'Pakan ternak'],
            ['Pasca Panen', 'Peralatan pasca panen'],
        ])->map(function ($row) use ($root) {
            return Category::query()->firstOrCreate(
                ['name' => $row[0], 'parent_id' => $root->id],
                ['description' => $row[1]]
            );
        });

        // Random extra leaf categories.
        $leafNames = [
            'Sayuran', 'Buah', 'Tanaman Hias', 'Bibit Pohon', 'Media Tanam',
            'Mulsa', 'Polybag', 'Sprayer', 'Cangkul', 'Sabit',
            'Pompa Air', 'Selang', 'Ember', 'Karung', 'Timbangan',
        ];

        foreach (collect($leafNames)->shuffle()->take(12) as $leaf) {
            $parent = $parents->random();
            Category::query()->firstOrCreate(
                ['name' => $leaf, 'parent_id' => $parent->id],
                ['description' => fake()->sentence()]
            );
        }
    }

    private function seedMoreItems(int $count): void
    {
        $categories = Category::query()->whereNotNull('parent_id')->inRandomOrder()->limit(30)->pluck('id');
        if ($categories->isEmpty()) {
            $categories = Category::query()->pluck('id');
        }

        $units = ['pcs', 'kg', 'liter', 'sak', 'botol', 'pack', 'roll'];

        for ($i = 0; $i < $count; $i++) {
            $sku = strtoupper('AGR-'.Str::random(3).'-'.fake()->unique()->numberBetween(1000, 9999));

            $item = Item::query()->firstOrNew(['sku' => $sku]);
            $item->name = fake()->words(fake()->numberBetween(2, 4), true);
            $item->category_id = $categories->random();
            $item->unit = fake()->randomElement($units);
            $item->description = fake()->boolean(70) ? fake()->sentence() : null;
            $item->image_path = null;
            $item->save();

            $item->stock = fake()->numberBetween(0, 120);
            $item->save();
        }
    }
}
