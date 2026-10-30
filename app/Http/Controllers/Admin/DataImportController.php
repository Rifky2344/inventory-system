<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use App\Models\Item;
use App\Models\Category;

class DataImportController extends Controller
{
    /**
     * Download template Excel untuk import Barang.
     * Dibuat bersih tanpa baris contoh agar tidak ada data dummy yang ikut terimpor.
     */
    public function itemsTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Barang');

        // Headers: SKU, Nama, Kategori, Satuan (ex: pcs), Stok, Deskripsi
        $headers = ['SKU *', 'Nama Barang *', 'Nama Kategori', 'Satuan (ex: pcs)', 'Stok', 'Deskripsi'];
        foreach ($headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
        }

        // Header style (emerald green, bold, centered)
        $sheet->getStyle('A1:F1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF10B981']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(28);

        // Auto width untuk semua kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_barang.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /**
     * Download template Excel untuk import Kategori.
     */
    public function categoriesTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Kategori');

        $headers = ['Nama Kategori *', 'Deskripsi'];
        foreach ($headers as $col => $header) {
            $cell = chr(65 + $col) . '1';
            $sheet->setCellValue($cell, $header);
        }

        $sheet->getStyle('A1:B1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF10B981']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension('1')->setRowHeight(28);

        foreach (range('A', 'B') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'template_import_kategori.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    public function items(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:5120',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $importedCount = 0;
        $duplicateSkus = [];
        $processedSkusInFile = [];

        // Skip header (row 0)
        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $sku  = trim((string)($row[0] ?? ''));
            $name = trim((string)($row[1] ?? ''));

            // Abaikan baris kosong
            if (empty($sku) || empty($name)) continue;

            // Cek apakah SKU sudah ada di database (termasuk yang soft-deleted) atau duplikat dalam file ini
            $isDuplicate = Item::withTrashed()->where('sku', $sku)->exists() || in_array($sku, $processedSkusInFile, true);
            if ($isDuplicate) {
                $duplicateSkus[] = $sku;
                continue;
            }

            // Kategori (opsional)
            $categoryName = trim((string)($row[2] ?? ''));
            $categoryId = null;
            if (!empty($categoryName)) {
                $category = Category::firstOrCreate(
                    ['name' => $categoryName],
                    ['description' => 'Auto-generated via import']
                );
                $categoryId = $category->id;
            }

            // Satuan (opsional, default 'pcs')
            $unit = trim((string)($row[3] ?? 'pcs')) ?: 'pcs';

            // Stok & Deskripsi (Mendukung format baru: A:SKU, B:Nama, C:Kategori, D:Satuan, E:Stok, F:Deskripsi)
            $stock = 0;
            $description = '';
            if (isset($row[4]) && is_numeric($row[4])) {
                $stock = max(0, (int)$row[4]);
                $description = trim((string)($row[5] ?? ''));
            } else {
                // Fallback jika pengguna menggunakan format lama tanpa kolom stok
                $description = trim((string)($row[4] ?? ''));
            }

            try {
                $item = new Item();
                $item->sku = $sku;
                $item->name = $name;
                $item->category_id = $categoryId;
                $item->unit = $unit;
                $item->stock = $stock;
                $item->description = $description;
                $item->save();

                $processedSkusInFile[] = $sku;
                $importedCount++;
            } catch (\Illuminate\Database\QueryException $e) {
                // Tangkap jika terjadi violation constraint lain tanpa membuat error 500
                $duplicateSkus[] = $sku;
            }
        }

        $duplicateSkus = array_values(array_unique($duplicateSkus));

        // Susun notifikasi yang informatif
        if ($importedCount > 0 && count($duplicateSkus) > 0) {
            return back()
                ->with('success', "Berhasil mengimpor $importedCount barang baru.")
                ->with('warning', "SKU berikut dilewati karena sudah ada di sistem: " . implode(', ', $duplicateSkus));
        }

        if ($importedCount === 0 && count($duplicateSkus) > 0) {
            return back()->with('error', "Gagal mengimpor data. Semua SKU berikut sudah terdaftar di sistem: " . implode(', ', $duplicateSkus));
        }

        if ($importedCount === 0 && count($duplicateSkus) === 0) {
            return back()->with('error', "Tidak ada data barang yang valid untuk diimpor. Pastikan file Excel terisi dengan benar.");
        }

        return back()->with('success', "Berhasil mengimpor $importedCount barang.");
    }

    public function categories(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv|max:5120',
        ]);

        $file = $request->file('file');
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $importedCount = 0;
        $existingNames = [];

        foreach ($rows as $index => $row) {
            if ($index === 0) continue;

            $name = trim((string)($row[0] ?? ''));
            if (empty($name)) continue;

            $exists = Category::where('name', $name)->exists();
            if ($exists) {
                $existingNames[] = $name;
                continue;
            }

            Category::create([
                'name' => $name,
                'description' => trim((string)($row[1] ?? '')),
            ]);
            $importedCount++;
        }

        $existingNames = array_values(array_unique($existingNames));

        if ($importedCount > 0 && count($existingNames) > 0) {
            return back()
                ->with('success', "Berhasil mengimpor $importedCount kategori baru.")
                ->with('warning', "Kategori berikut dilewati karena sudah ada: " . implode(', ', $existingNames));
        }

        if ($importedCount === 0 && count($existingNames) > 0) {
            return back()->with('error', "Gagal mengimpor. Kategori berikut sudah terdaftar di sistem: " . implode(', ', $existingNames));
        }

        if ($importedCount === 0) {
            return back()->with('error', "Tidak ada data kategori yang valid untuk diimpor.");
        }

        return back()->with('success', "Berhasil mengimpor $importedCount kategori.");
    }
}
