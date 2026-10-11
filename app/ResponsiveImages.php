<?php

namespace App;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ResponsiveImages
{
    public const WIDTHS = [160, 320, 640, 800, 960, 1280];

    /**
     * @return array{src: string, srcset?: string, width?: int, height?: int}
     */
    public function attributes(string $url): array
    {
        $host = parse_url($url, PHP_URL_HOST);
        $path = rawurldecode(parse_url($url, PHP_URL_PATH) ?: '');

        if (($host && $host !== parse_url(url('/'), PHP_URL_HOST)) || ! str_starts_with($path, '/storage/')) {
            return ['src' => $url];
        }

        $relativePath = substr($path, strlen('/storage/'));
        $source = $this->source($relativePath);

        if ($source === null) {
            return ['src' => $url];
        }

        $candidates = [];

        foreach (self::WIDTHS as $width) {
            $actualWidth = min($width, $source['width']);
            $candidates[$actualWidth] ??= $this->url($relativePath, $width, $source['version']).' '.$actualWidth.'w';
        }

        return [
            'src' => $this->url($relativePath, 960, $source['version']),
            'srcset' => implode(', ', $candidates),
            'width' => $source['width'],
            'height' => $source['height'],
        ];
    }

    /**
     * @return array{path: string, width: int, height: int, type: int, modified: int, version: string}|null
     */
    public function source(string $path): ?array
    {
        if (! function_exists('imagewebp') || str_contains($path, "\0")) {
            return null;
        }

        $root = realpath(Storage::disk('public')->path(''));
        $absolutePath = realpath(Storage::disk('public')->path($path));

        if (! $root || ! $absolutePath || ! str_starts_with($absolutePath, $root.DIRECTORY_SEPARATOR) || ! is_file($absolutePath)) {
            return null;
        }

        clearstatcache(true, $absolutePath);
        $bytes = filesize($absolutePath);

        if ($bytes > 20 * 1024 * 1024) {
            return null;
        }

        $dimensions = @getimagesize($absolutePath);

        if (! $dimensions || ! in_array($dimensions[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP], true)
            || $dimensions[0] * $dimensions[1] > 16000000) {
            return null;
        }

        if ($dimensions[2] === IMAGETYPE_WEBP) {
            $header = file_get_contents($absolutePath, false, null, 0, 21);

            if (substr($header, 12, 4) === 'VP8X' && (ord($header[20]) & 2)) {
                return null;
            }
        }

        if ($dimensions[2] === IMAGETYPE_JPEG) {
            if (! function_exists('exif_read_data')) {
                return null;
            }

            $metadata = @exif_read_data($absolutePath, 'IFD0');

            if (($metadata['Orientation'] ?? 1) !== 1) {
                return null;
            }
        }

        $modified = filemtime($absolutePath);

        return [
            'path' => $absolutePath,
            'width' => $dimensions[0],
            'height' => $dimensions[1],
            'type' => $dimensions[2],
            'modified' => $modified,
            'version' => substr(hash('sha256', $absolutePath.'|'.hash_file('sha256', $absolutePath)), 0, 16),
        ];
    }

    /**
     * @param  array{path: string, width: int, height: int, type: int, modified: int, version: string}  $source
     */
    public function generate(array $source, int $width): string
    {
        $cachePath = Storage::disk('local')->path('image-cache/'.$source['version'].'-'.$width.'.webp');

        if (is_file($cachePath)) {
            return $cachePath;
        }

        $original = match ($source['type']) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($source['path']),
            IMAGETYPE_PNG => @imagecreatefrompng($source['path']),
            IMAGETYPE_WEBP => @imagecreatefromwebp($source['path']),
        };

        abort_unless($original, 404);

        $targetWidth = min($width, $source['width']);
        $targetHeight = max(1, (int) round($source['height'] * $targetWidth / $source['width']));
        $resized = imagecreatetruecolor($targetWidth, $targetHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        imagecopyresampled($resized, $original, 0, 0, 0, 0, $targetWidth, $targetHeight, $source['width'], $source['height']);

        File::ensureDirectoryExists(dirname($cachePath));
        $temporaryPath = tempnam(dirname($cachePath), 'image-');

        try {
            abort_unless(imagewebp($resized, $temporaryPath, 82), 500);
            abort_unless(File::move($temporaryPath, $cachePath), 500);
        } finally {
            File::delete($temporaryPath);
        }

        return $cachePath;
    }

    private function url(string $path, int $width, string $version): string
    {
        return route('images.show', ['width' => $width, 'path' => $path, 'v' => $version]);
    }
}
