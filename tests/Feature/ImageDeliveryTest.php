<?php

namespace Tests\Feature;

use App\ResponsiveImages;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageDeliveryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
        Storage::fake('local');
    }

    public function test_public_images_are_resized_cached_and_revalidated(): void
    {
        $path = UploadedFile::fake()->image('preview.png', 1600, 900)->storeAs('products', 'preview.png', 'public');
        $images = app(ResponsiveImages::class);
        $source = $images->source($path);
        $url = route('images.show', ['width' => 640, 'path' => $path, 'v' => $source['version']]);

        $response = $this->get($url)->assertOk()->assertHeader('Content-Type', 'image/webp');
        $response->assertHeader('Cache-Control', 'immutable, max-age=31536000, public');
        $this->assertFalse($response->headers->has('Set-Cookie'));

        $cachedPath = $response->baseResponse->getFile()->getPathname();
        $dimensions = getimagesize($cachedPath);
        $this->assertSame(640, $dimensions[0]);
        $this->assertSame(360, $dimensions[1]);
        $this->assertSame(IMAGETYPE_WEBP, $dimensions[2]);
        $this->assertSame($cachedPath, $images->generate($source, 640));
        $this->get($url, ['If-None-Match' => $response->headers->get('ETag')])->assertStatus(304);
    }

    public function test_small_images_are_not_upscaled_and_updated_sources_change_urls(): void
    {
        UploadedFile::fake()->image('small.png', 100, 50)->storeAs('products', 'small.png', 'public');
        $images = app(ResponsiveImages::class);
        $before = $images->attributes(asset('storage/products/small.png'));
        $response = $this->get($before['src'])->assertOk();
        $dimensions = getimagesize($response->baseResponse->getFile()->getPathname());

        $this->assertSame(100, $dimensions[0]);
        $this->assertSame(50, $dimensions[1]);
        $this->assertStringEndsWith('100w', $before['srcset']);
        $this->assertStringNotContainsString(',', $before['srcset']);

        UploadedFile::fake()->image('small.png', 200, 100)->storeAs('products', 'small.png', 'public');
        $this->assertNotSame($before['src'], $images->attributes(asset('storage/products/small.png'))['src']);
    }

    public function test_external_missing_and_gif_images_keep_their_original_urls(): void
    {
        $images = app(ResponsiveImages::class);
        Storage::disk('public')->put('animation.gif', base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'));

        foreach (['https://example.com/storage/photo.png', asset('storage/missing.png'), asset('storage/animation.gif')] as $url) {
            $this->assertSame(['src' => $url], $images->attributes($url));
        }
    }

    public function test_private_files_invalid_images_and_unsupported_sizes_are_rejected(): void
    {
        Storage::disk('local')->put('private.png', UploadedFile::fake()->image('private.png')->getContent());
        Storage::disk('public')->put('invalid.png', 'not an image');
        $this->get('/images/640/missing.png')->assertNotFound();
        $this->get('/images/640/invalid.png')->assertNotFound();
        $this->get('/images/641/invalid.png')->assertNotFound();

        symlink(Storage::disk('local')->path('private.png'), Storage::disk('public')->path('escape.png'));
        $this->get('/images/640/escape.png')->assertNotFound();
        $this->assertNull(app(ResponsiveImages::class)->source('../local/private.png'));
    }

    public function test_unversioned_images_require_revalidation(): void
    {
        UploadedFile::fake()->image('preview.png')->storeAs('products', 'preview.png', 'public');

        $this->get('/images/320/products/preview.png')
            ->assertOk()
            ->assertHeader('Cache-Control', 'max-age=0, must-revalidate, public');
    }

    public function test_same_size_replacements_with_the_same_timestamp_change_the_image_version(): void
    {
        $disk = Storage::disk('public');
        $image = imagecreatetruecolor(20, 20);
        imagepng($image, $disk->path('replace.png'), 0);
        $images = app(ResponsiveImages::class);
        $before = $images->source('replace.png');
        $bytes = filesize($disk->path('replace.png'));

        imagefill($image, 0, 0, imagecolorallocate($image, 255, 0, 0));
        imagepng($image, $disk->path('replace.png'), 0);
        touch($disk->path('replace.png'), $before['modified']);
        $after = $images->source('replace.png');

        $this->assertSame($bytes, filesize($disk->path('replace.png')));
        $this->assertSame($before['modified'], $after['modified']);
        $this->assertNotSame($before['version'], $after['version']);
    }

    public function test_animated_webp_and_oriented_jpeg_are_preserved(): void
    {
        $disk = Storage::disk('public');
        $image = imagecreatetruecolor(20, 10);
        imagewebp($image, $disk->path('still.webp'));
        $webp = $disk->get('still.webp');
        $extended = 'VP8X'.pack('V', 10)."\x02\0\0\0\x13\0\0\x09\0\0".substr($webp, 12);
        $disk->put('animated.webp', 'RIFF'.pack('V', strlen($extended) + 4).'WEBP'.$extended);
        $this->assertNull(app(ResponsiveImages::class)->source('animated.webp'));

        if (! function_exists('exif_read_data')) {
            return;
        }

        imagejpeg($image, $disk->path('still.jpg'));
        $jpeg = $disk->get('still.jpg');
        $exif = "Exif\0\0MM\0*\0\0\0\x08\0\x01\x01\x12\0\x03\0\0\0\x01\0\x06\0\0\0\0\0\0";
        $disk->put('rotated.jpg', substr($jpeg, 0, 2)."\xff\xe1".pack('n', strlen($exif) + 2).$exif.substr($jpeg, 2));
        $this->assertSame(6, exif_read_data($disk->path('rotated.jpg'), 'IFD0')['Orientation']);
        $this->assertNull(app(ResponsiveImages::class)->source('rotated.jpg'));
    }
}
