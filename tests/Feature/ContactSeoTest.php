<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ContactSeoTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_contact_page_has_complete_seo_metadata(): void
    {
        $response = $this->get(route('contact.create'));

        $response->assertOk();
        $response->assertSee('<title>Liên hệ triển khai website và source code - NQT Dev</title>', false);
        $response->assertSee('<meta name="description" content="Gửi brief cho NQT Dev để tư vấn triển khai website, cửa hàng điện tử, source code Laravel, WordPress và hệ thống web theo yêu cầu.">', false);
        $response->assertSee('<link rel="canonical" href="'.route('contact.create').'">', false);
        $response->assertSee('<meta property="og:type" content="website">', false);
        $response->assertSee('<meta property="og:url" content="'.route('contact.create').'">', false);
        $response->assertSee('<meta name="twitter:card" content="summary_large_image">', false);
        $response->assertSee('<meta name="twitter:url" content="'.route('contact.create').'">', false);
        $response->assertSee('<meta name="twitter:image" content="'.asset('logo.png').'">', false);
        $response->assertSee('"@type":"ContactPage"', false);
    }
}
