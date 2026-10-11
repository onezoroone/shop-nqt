@props(['src', 'alt' => '', 'sizes' => '100vw'])
@inject('responsiveImages', 'App\ResponsiveImages')

<img {{ $attributes->merge(array_merge($responsiveImages->attributes($src), [
    'alt' => $alt,
    'sizes' => $sizes,
    'decoding' => 'async',
])) }}>
