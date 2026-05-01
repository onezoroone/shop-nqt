<?php
$files = [
    'resources/views/products/_card.blade.php',
    'resources/views/products/show.blade.php',
    'resources/views/projects/index.blade.php',
    'resources/views/projects/show.blade.php',
    'resources/views/cart/index.blade.php',
    'resources/views/orders/show.blade.php',
    'resources/views/home.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    // Replace {{ asset('storage/' . $product->thumbnail) }} or similar
    $newContent = preg_replace("/\{\{\s*asset\('storage\/'\s*\.\s*\\$([a-zA-Z0-9_]+)->thumbnail\)\s*\}\}/", "{{\\$1->thumbnail_url}}", $content);
    // Also handle array keys like $item['product']->thumbnail
    $newContent = preg_replace("/\{\{\s*asset\('storage\/'\s*\.\s*\\$([a-zA-Z0-9_]+)\['product'\]->thumbnail\)\s*\}\}/", "{{\\$1['product']->thumbnail_url}}", $newContent);
    
    // Also handle $related->thumbnail
    $newContent = preg_replace("/\{\{\s*asset\('storage\/'\s*\.\s*\\$([a-zA-Z0-9_]+)->thumbnail\)\s*\}\}/", "{{\\$1->thumbnail_url}}", $newContent);

    if ($content !== $newContent) {
        file_put_contents($file, $newContent);
        echo "Updated $file\n";
    }
}
