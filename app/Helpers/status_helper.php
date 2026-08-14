<?php
if (!function_exists('statusLabel')) {
    function statusLabel(string $status): string {
        $map = [
            'Unpaid' => 'Belum Dibayar',
            'Partially Paid' => 'Sebagian Dibayar',
            'Paid' => 'Lunas',
            'Overdue' => 'Terlambat',
            'Pending' => 'Menunggu',
            'Completed' => 'Selesai',
        ];
        return $map[$status] ?? $status;
    }
}
?>
