<?php

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');

if ($uri === '/' && extension_loaded('zlib')) {
    header('Vary: Accept-Encoding', false);

    foreach (explode(',', $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '') as $acceptedEncoding) {
        $parts = explode(';', $acceptedEncoding, 2);

        if (strtolower(trim($parts[0])) !== 'gzip') {
            continue;
        }

        $quality = preg_match('/\bq\s*=\s*([\d.]+)/i', $parts[1] ?? '', $matches)
            ? (float) $matches[1]
            : 1.0;

        if ($quality > 0 && $quality <= 1) {
            ini_set('zlib.output_compression', '1');
            ini_set('zlib.output_compression_level', '6');
        }

        break;
    }
}

return require __DIR__.'/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php';
