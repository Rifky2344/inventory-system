# Status Translation and UI Update Implementation Plan

**Goal**: Translate all English status labels displayed in the UI and Excel exports to Indonesian, while preserving underlying database values.

## User Review Required

> [!IMPORTANT]
> Verify the Indonesian translations for each status match the terminology used in your organization. If any translation needs adjustment, please provide the preferred wording.

## Open Questions

- Are there any additional status fields (e.g., order status, delivery status) not listed that also need translation?
- Should we also translate tooltip texts or only the visible labels?

## Proposed Changes

### 1. Blade Views
- Update status display in the following view files:
  - `resources/views/invoices/index.blade.php`
  - `resources/views/invoices/show.blade.php`
  - `resources/views/installment-plans/index.blade.php`
  - `resources/views/installment-plans/show.blade.php`
  - `resources/views/sales-visits/index.blade.php`
  - `resources/views/sales-visits/show.blade.php`
  - `resources/views/sales-visits/create.blade.php`
  - `resources/views/sales-visits/edit.blade.php`
  - `resources/views/payments/create.blade.php`
  - `resources/views/dashboard.blade.php`
- Replace hard‑coded English status strings with a helper function `statusLabel($status)` that returns the Indonesian equivalent.
- Add the helper function to a new file `app/Helpers/status_helper.php` and autoload it via Composer's `files` autoload.

### 2. Export Controller
- Modify `app/Http/Controllers/InventoryExportController.php` to map status values to Indonesian before writing them to the spreadsheet.
- Use the same mapping logic as the helper to keep consistency.

### 3. Helper Mapping
```php
<?php
// app/Helpers/status_helper.php
function statusLabel(string $status): string {
    $map = [
        'Unpaid' => 'Belum Dibayar',
        'Partially Paid' => 'Sebagian Dibayar',
        'Paid' => 'Lunas',
        'Overdue' => 'Terlambat',
        'Pending' => 'Menunggu',
        'Completed' => 'Selesai',
        // add more mappings as needed
    ];
    return $map[$status] ?? $status;
}
?>
```
- Register the file in `composer.json` under `autoload.files` and run `composer dump-autoload`.

### 4. UI Consistency
- Ensure any status badges or colors remain unchanged; only the text is translated.
- Verify that the dashboard cards showing status counts use the translated labels.

## Verification Plan

### Automated Tests
- Run existing unit/feature tests (`php artisan test`) to ensure no breakage.
- Add a quick sanity test to confirm `statusLabel('Paid')` returns `'Lunas'`.

### Manual Verification
- Load the application in a browser and navigate to each of the updated pages, confirming that status labels appear in Indonesian.
- Export an Excel file via the inventory export and open it to verify the status columns are translated.

---
*Prepared by Antigravity*
