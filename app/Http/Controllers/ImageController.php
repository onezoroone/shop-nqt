<?php

namespace App\Http\Controllers;

use App\ResponsiveImages;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImageController extends Controller
{
    public function __invoke(Request $request, ResponsiveImages $images, int $width, string $path): BinaryFileResponse
    {
        $source = $images->source($path);
        abort_if($source === null, 404);

        $versioned = $request->query('v') === $source['version'];
        $response = response()->file($images->generate($source, $width), [
            'Content-Type' => 'image/webp',
            'Cache-Control' => $versioned ? 'public, max-age=31536000, immutable' : 'public, max-age=0, must-revalidate',
            'X-Content-Type-Options' => 'nosniff',
        ]);
        $response->setEtag($source['version'].'-'.$width);
        $response->isNotModified($request);

        return $response;
    }
}
