<?php
$views = [
    'resources/views/items/index.blade.php' => '$items',
    'resources/views/transactions/index.blade.php' => '$transactions',
    'resources/views/mutations/index.blade.php' => '$mutations',
    'resources/views/customers/index.blade.php' => '$customers',
    'resources/views/sales-visits/index.blade.php' => '$visits',
    'resources/views/invoices/index.blade.php' => '$invoices',
    'resources/views/payments/index.blade.php' => '$payments',
    'resources/views/installment-plans/index.blade.php' => '$invoices',
];

foreach ($views as $view => $var) {
    if (!file_exists($view)) {
        echo 'Missing file ' . $view . PHP_EOL;
        continue;
    }
    $content = file_get_contents($view);
    
    // Look for the general links div with regex to handle varying whitespace
    $pattern = '/<div class="mt-4">\s*\{\{\s*\\' . $var . '->links\(\)\s*\}\}\s*<\/div>/i';
    
    $replacement = '<div class="mt-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex-grow">
            {{ ' . $var . '->links() }}
        </div>
        <div class="flex items-center gap-2 text-sm text-gray-600">
            <label for="per_page_' . str_replace('$', '', $var) . '">Tampilkan:</label>
            <select id="per_page_' . str_replace('$', '', $var) . '" class="rounded-md border border-gray-300 py-1.5 pl-3 pr-8 text-sm focus:border-emerald-300 focus:ring focus:ring-emerald-200/50" onchange="window.location.href = this.value">
                @foreach([15, 25, 50, 100] as $size)
                    <option value="{{ request()->fullUrlWithQuery([\'per_page\' => $size]) }}" @selected(request(\'per_page\', 15) == $size)>
                        {{ $size }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>';
    
    $newContent = preg_replace($pattern, $replacement, $content);
    
    if ($newContent !== null && $newContent !== $content) {
        file_put_contents($view, $newContent);
        echo 'Updated ' . $view . PHP_EOL;
    } else {
        echo 'Could not find target in ' . $view . PHP_EOL;
    }
}
echo "Done\n";
