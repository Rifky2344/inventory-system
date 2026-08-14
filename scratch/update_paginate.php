<?php
$files = array_merge(glob('app/Http/Controllers/*.php'), glob('app/Http/Controllers/*/*.php'));
foreach($files as $file) {
    $content = file_get_contents($file);
    // Replace paginate(15) or paginate(20) with paginate(request('per_page', $1))
    $newContent = preg_replace('/paginate\((\d+)\)/', 'paginate(request(\'per_page\', $1))', $content);
    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo 'Updated ' . $file . PHP_EOL;
    }
}
echo 'Done';
