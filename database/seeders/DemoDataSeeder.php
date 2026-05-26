<?php

namespace Database\Seeders;

use App\Enums\RoleSlug;
use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Item;
use App\Models\Role;
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
            ->whereIn('slug', [RoleSlug::SUPER_ADMIN->value, RoleSlug::ADMIN->value, RoleSlug::STAFF_GUDANG->value])
            ->get()
            ->keyBy('slug');

        $superAdminRoleId = $roles[RoleSlug::SUPER_ADMIN->value]->id ?? null;
        $adminRoleId = $roles[RoleSlug::ADMIN->value]->id ?? null;
        $staffRoleId = $roles[RoleSlug::STAFF_GUDANG->value]->id ?? null;

        if (!$superAdminRoleId || !$adminRoleId || !$staffRoleId) {
            // Roles must be seeded first.
            return;
        }

        // Create a few known accounts.
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

        // Additional users.
        $admins = User::factory()->count(8)->create(['role_id' => $adminRoleId]);
        $staffs = User::factory()->count(30)->create(['role_id' => $staffRoleId]);

        $actorPool = $admins->concat($staffs)->push($admin)->push($staff);

        // Ensure we have a good amount of categories/items.
        if (Category::query()->count() < 25) {
            $this->seedMoreCategories();
        }

        if (Item::query()->count() < 80) {
            $this->seedMoreItems(75);
        }

        // Transactions + mutations + logs.
        $items = Item::query()->inRandomOrder()->limit(80)->get();
        $startDate = now()->subDays(45);

        /** @var array<string, int> $sequenceByKey */
        $sequenceByKey = [];

        for ($i = 0; $i < 250; $i++) {
            /** @var Item $item */
            $item = $items->random();

            $type = fake()->randomElement([StockTransaction::TYPE_IN, StockTransaction::TYPE_OUT]);
            $date = Carbon::instance(fake()->dateTimeBetween($startDate, now()))->startOfDay();

            // Keep OUT transactions realistic (don't make stock negative).
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

            /** @var User $actor */
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

            // Update stock.
            $item->stock = $stockAfter;
            $item->save();

            // Mutation record.
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

            // Activity log.
            ActivityLog::create([
                'user_id' => $actor->id,
                'action' => $type === StockTransaction::TYPE_IN ? 'transaction_in_create' : 'transaction_out_create',
                'subject_type' => StockTransaction::class,
                'subject_id' => $transaction->id,
                'ip_address' => fake()->ipv4(),
                'user_agent' => fake()->userAgent(),
                'meta' => [
                    'transaction_number' => $transactionNumber,
                    'item_id' => $item->id,
                    'quantity' => $qty,
                ],
                'created_at' => $transaction->created_at,
            ]);
        }

        // Some login events.
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

        // A few admin CRUD logs.
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
