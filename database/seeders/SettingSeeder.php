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
            'usdt_wallet_address' => 'TRC20: TKQJUDn75tTRmmz3TMADj9xk3NBDN3qtLw'
        ];

        foreach ($settings as $key => $value) {
            Setting::create(['key' => $key, 'value' => $value]);
        }
    }
}
