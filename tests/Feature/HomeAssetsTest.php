<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\ViteException;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\HtmlString;
use Tests\TestCase;

class HomeAssetsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_home_inlines_its_styles_and_preloads_local_fonts(): void
    {
        Vite::shouldReceive('isRunningHot')->andReturn(false);
        Vite::shouldReceive('asset')->andReturnUsing(fn (string $path): string => '/build/'.basename($path));
        Vite::shouldReceive('content')->once()->with('resources/css/home-critical.css')->andReturn('.home-page{color:green}');
        Vite::shouldReceive('__invoke')->once()->with('resources/js/app.js')->andReturn(new HtmlString('<script src="/build/app.js" type="module"></script>'));

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('<style>.home-page{color:green}</style>', false);
        $response->assertSee('/build/space-grotesk-latin-vietnamese.woff2', false);
        $response->assertSee('/build/ibm-plex-sans-latin-vietnamese.woff2', false);
        $response->assertSee('/build/jetbrains-mono-latin-vietnamese.woff2', false);
        $response->assertDontSee('fonts.googleapis.com', false);
        $response->assertDontSee('rel="stylesheet"', false);
    }

    public function test_home_uses_normal_vite_assets_during_development(): void
    {
        Vite::shouldReceive('isRunningHot')->andReturn(true);
        Vite::shouldReceive('asset')->never();
        Vite::shouldReceive('content')->never();
        $this->expectNormalStylesheets();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('/build/normal.css', false);
        $response->assertDontSee('rel="preload"', false);
    }

    public function test_home_falls_back_when_an_older_build_has_no_combined_fonts(): void
    {
        Vite::shouldReceive('isRunningHot')->andReturn(false);
        Vite::shouldReceive('asset')->once()->andThrow(new ViteException('Missing font in older manifest.'));
        Vite::shouldReceive('content')->never();
        $this->expectNormalStylesheets();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('/build/normal.css', false);
        $response->assertDontSee('rel="preload"', false);
    }

    public function test_home_falls_back_when_an_older_build_has_no_inline_stylesheet(): void
    {
        Vite::shouldReceive('isRunningHot')->andReturn(false);
        Vite::shouldReceive('asset')->andReturnUsing(fn (string $path): string => '/build/'.basename($path));
        Vite::shouldReceive('content')->once()->andThrow(new ViteException('Missing stylesheet in older manifest.'));
        $this->expectNormalStylesheets();

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('/build/normal.css', false);
        $response->assertDontSee('rel="preload"', false);
    }

    private function expectNormalStylesheets(): void
    {
        Vite::shouldReceive('__invoke')->once()->with([
            'resources/css/app.css',
            'resources/css/home.css',
            'resources/js/app.js',
        ])->andReturn(new HtmlString('<link rel="stylesheet" href="/build/normal.css">'));
    }
}
