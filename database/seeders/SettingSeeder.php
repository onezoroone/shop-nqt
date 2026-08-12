<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'site_name' => 'NQT Dev',
            'site_tagline' => 'Full-Stack Developer & Digital Craftsman',
            'site_description' => 'Portfolio and digital product shop by NQT — a passionate full-stack developer specializing in Laravel, WordPress, and modern web technologies.',
            'hero_title' => 'Crafting Digital Experiences',
            'hero_subtitle' => 'Full-Stack Developer specializing in Laravel, WordPress, and scalable web applications. Turning ideas into elegant, high-performance solutions.',
            'about_text' => 'I\'m a passionate full-stack developer with years of experience building web applications, content platforms, and automation tools. I love turning complex problems into simple, beautiful solutions. My expertise spans from backend systems with Laravel and PHP to frontend interfaces with Vue.js and TailwindCSS.',
            'contact_email' => 'contact@nqtdev.com',
            'github_url' => 'https://github.com/onezoroone',
            'linkedin_url' => '',
            'twitter_url' => '',
            'footer_text' => '© 2026 NQT Dev. Built with Laravel & TailwindCSS.',
            'telegram_url' => 'https://t.me/congthangdz',
            'usdt_wallet_address' => 'TRC20: TKQJUDn75tTRmmz3TMADj9xk3NBDN3qtLw',
            'seo_home_title' => 'NQT Dev - Portfolio & Store',
            'seo_home_description' => 'Portfolio and digital product shop by NQT — a passionate full-stack developer.',
            'seo_home_keywords' => 'laravel, vuejs, full-stack, developer, shop',
            'seo_products_title' => 'Cửa hàng Sản phẩm - NQT Dev',
            'seo_products_description' => 'Khám phá các sản phẩm và source code chất lượng cao từ NQT.',
            'seo_products_keywords' => 'source code, php, laravel, web templates',
            'seo_projects_title' => 'Dự án nổi bật - NQT Dev',
            'seo_projects_description' => 'Các dự án tiêu biểu mà tôi đã thực hiện.',
            'seo_projects_keywords' => 'portfolio, projects, web development',
            'seo_contact_title' => 'Liên hệ triển khai website và source code - NQT Dev',
            'seo_contact_description' => 'Gửi brief cho NQT Dev để tư vấn triển khai website, cửa hàng điện tử, source code Laravel, WordPress và hệ thống web theo yêu cầu.',
            'seo_contact_keywords' => 'liên hệ lập trình viên, thiết kế website, source code Laravel, WordPress, cửa hàng điện tử',
            'seo_default_image' => asset('logo.png'),
        ];

        foreach ($settings as $key => $value) {
            Setting::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
