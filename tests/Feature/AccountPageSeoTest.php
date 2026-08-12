<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class AccountPageSeoTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_cart_has_metadata_and_is_not_indexable(): void
    {
        $response = $this->get(route('cart.index'));

        $response->assertOk();
        $response->assertSee('<title>Giỏ hàng - NQT Dev</title>', false);
        $response->assertSee('<link rel="canonical" href="'.route('cart.index').'">', false);
        $response->assertSee('<meta name="robots" content="noindex, follow, noarchive">', false);
        $this->assertNoShareMetadata($response);
    }

    public function test_login_redirect_target_has_private_page_metadata(): void
    {
        $response = $this->followingRedirects()->get('/orders/25');

        $response->assertOk();
        $response->assertSee('<title>Đăng nhập tài khoản - NQT Dev</title>', false);
        $response->assertSee('<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">', false);
        $this->assertNoShareMetadata($response);
    }

    public function test_authenticated_account_pages_have_private_page_metadata(): void
    {
        $user = User::factory()->create();
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => 49.99,
            'status' => 'pending',
            'payment_method' => 'usdt',
        ]);

        $this->actingAs($user);

        $pages = [
            [route('dashboard'), 'Tài khoản của tôi - NQT Dev'],
            [route('orders.index'), 'Đơn hàng của tôi - NQT Dev'],
            [route('orders.show', $order), "Chi tiết đơn hàng #{$order->id} - NQT Dev"],
        ];

        foreach ($pages as [$url, $title]) {
            $response = $this->get($url);

            $response->assertOk();
            $response->assertSee("<title>{$title}</title>", false);
            $response->assertSee('<meta name="robots" content="noindex, nofollow, noarchive, nosnippet">', false);
            $this->assertNoShareMetadata($response);
        }
    }

    private function assertNoShareMetadata(TestResponse $response): void
    {
        $response->assertDontSee('property="og:', false);
        $response->assertDontSee('name="twitter:', false);
        $response->assertDontSee('application/ld+json', false);
    }
}
