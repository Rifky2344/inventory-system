<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $this->upsertItem(
            sku: 'ATK-A4-80',
            name: 'Kertas HVS A4 80gsm (1 rim)',
            categoryName: 'Kertas',
            unit: 'rim',
            stock: 10,
            description: 'Kertas HVS A4 80gsm untuk kebutuhan cetak.'
        );

        $this->upsertItem(
            sku: 'ATK-A4-70',
            name: 'Kertas HVS A4 70gsm (1 rim)',
            categoryName: 'Kertas',
            unit: 'rim',
            stock: 8,
            description: 'Kertas HVS A4 70gsm untuk kebutuhan harian.'
        );

        $this->upsertItem(
            sku: 'ATK-PEN-BLK',
            name: 'Pulpen Hitam',
            categoryName: 'Pulpen & Spidol',
            unit: 'pcs',
            stock: 50,
            description: 'Pulpen tinta hitam.'
        );

        $this->upsertItem(
            sku: 'ATK-MARK-RED',
            name: 'Spidol Merah',
            categoryName: 'Pulpen & Spidol',
            unit: 'pcs',
            stock: 20,
            description: 'Spidol permanen warna merah.'
        );

        $this->upsertItem(
            sku: 'EL-TONER-HP12A',
            name: 'Toner Printer HP 12A',
            categoryName: 'Tinta & Toner',
            unit: 'pcs',
            stock: 5,
            description: 'Toner kompatibel HP 12A (Q2612A).'
        );

        $this->upsertItem(
            sku: 'EL-MOUSE-USB',
            name: 'Mouse USB',
            categoryName: 'Perangkat Komputer',
            unit: 'pcs',
            stock: 12,
            description: 'Mouse USB untuk operasional kantor.'
        );

        $this->upsertItem(
            sku: 'EL-KEYB-USB',
            name: 'Keyboard USB',
            categoryName: 'Perangkat Komputer',
            unit: 'pcs',
            stock: 6,
            description: 'Keyboard USB untuk operasional kantor.'
        );

        $this->upsertItem(
            sku: 'CLN-FLOOR-1L',
            name: 'Pembersih Lantai 1L',
            categoryName: 'Pembersih Lantai',
            unit: 'botol',
            stock: 15,
            description: 'Cairan pembersih lantai 1 liter.'
        );

        $this->upsertItem(
            sku: 'CLN-TISSUE-BOX',
            name: 'Tissue Box',
            categoryName: 'Tisu & Tissue',
            unit: 'box',
            stock: 24,
            description: 'Tissue box untuk ruang kerja.'
        );
    }

    private function upsertItem(
        string $sku,
        string $name,
        string $categoryName,
        string $unit,
        int $stock,
        ?string $description = null
    ): void {
        $categoryId = Category::query()->where('name', $categoryName)->value('id');
        if (!$categoryId) {
            return;
        }

        $item = Item::withTrashed()->firstOrNew(['sku' => $sku]);
        if ($item->exists && method_exists($item, 'trashed') && $item->trashed()) {
            $item->restore();
        }
        $item->name = $name;
        $item->category_id = $categoryId;
        $item->unit = $unit;
        $item->description = $description;
        $item->save();

        // stock tidak ada di fillable, jadi set langsung.
        $item->stock = $stock;
        $item->save();
    }
}
