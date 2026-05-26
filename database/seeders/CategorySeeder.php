<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $parents = [];

        $parents['Persediaan'] = Category::firstOrCreate(
            ['name' => 'Persediaan', 'parent_id' => null],
            ['description' => 'Kategori utama untuk persediaan gudang.']
        );

        $parents['ATK'] = Category::firstOrCreate(
            ['name' => 'ATK', 'parent_id' => $parents['Persediaan']->id],
            ['description' => 'Alat tulis kantor.']
        );

        $parents['Kebersihan'] = Category::firstOrCreate(
            ['name' => 'Kebersihan', 'parent_id' => $parents['Persediaan']->id],
            ['description' => 'Perlengkapan kebersihan.']
        );

        $parents['Elektronik'] = Category::firstOrCreate(
            ['name' => 'Elektronik', 'parent_id' => $parents['Persediaan']->id],
            ['description' => 'Perangkat elektronik untuk operasional.']
        );

        Category::firstOrCreate(
            ['name' => 'Kertas', 'parent_id' => $parents['ATK']->id],
            ['description' => 'Kertas untuk kebutuhan kantor.']
        );

        Category::firstOrCreate(
            ['name' => 'Pulpen & Spidol', 'parent_id' => $parents['ATK']->id],
            ['description' => 'Pulpen, spidol, dan alat tulis lainnya.']
        );

        Category::firstOrCreate(
            ['name' => 'Tinta & Toner', 'parent_id' => $parents['Elektronik']->id],
            ['description' => 'Consumable untuk printer.']
        );

        Category::firstOrCreate(
            ['name' => 'Perangkat Komputer', 'parent_id' => $parents['Elektronik']->id],
            ['description' => 'Mouse, keyboard, kabel, dan perangkat pendukung.']
        );

        Category::firstOrCreate(
            ['name' => 'Pembersih Lantai', 'parent_id' => $parents['Kebersihan']->id],
            ['description' => 'Pembersih lantai dan cairan kebersihan.']
        );

        Category::firstOrCreate(
            ['name' => 'Tisu & Tissue', 'parent_id' => $parents['Kebersihan']->id],
            ['description' => 'Tisu, tissue, dan sejenisnya.']
        );
    }
}
