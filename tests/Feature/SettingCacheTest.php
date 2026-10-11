<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SettingCacheTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_repeated_setting_lookups_read_the_underlying_cache_once(): void
    {
        config(['cache.default' => 'database']);
        Cache::memo()->flush();
        Setting::setValue('hero_title', 'Release desk');
        Setting::getValue('hero_title');
        DB::enableQueryLog();
        DB::flushQueryLog();

        $this->assertSame('Release desk', Setting::getValue('hero_title'));
        $this->assertSame('fallback', Setting::getValue('missing', 'fallback'));
        $this->assertSame('Release desk', Setting::getValue('hero_title'));
        $this->assertCount(1, DB::getQueryLog());
        DB::disableQueryLog();
    }

    public function test_setting_writes_and_deletes_invalidate_the_memoized_cache(): void
    {
        Setting::setValue('hero_title', 'Original');
        $this->assertSame('Original', Setting::getValue('hero_title'));
        Setting::setValue('hero_title', 'Updated');
        $this->assertSame('Updated', Setting::getValue('hero_title'));

        Setting::where('key', 'hero_title')->firstOrFail()->delete();
        $this->assertSame('fallback', Setting::getValue('hero_title', 'fallback'));
    }
}
