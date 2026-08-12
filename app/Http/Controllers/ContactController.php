<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\Setting;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        $this->configureSeo();

        return view('contact', [
            'contactEmail' => Setting::getValue('contact_email', ''),
            'telegramUrl' => Setting::getValue('telegram_url', ''),
            'githubUrl' => Setting::getValue('github_url', ''),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()->route('contact.create')
            ->with('success', 'Thank you for your message! I\'ll get back to you soon.');
    }

    private function configureSeo(): void
    {
        $title = Setting::getValue('seo_contact_title', 'Liên hệ triển khai website và source code - NQT Dev');
        $description = Setting::getValue(
            'seo_contact_description',
            'Gửi brief cho NQT Dev để tư vấn triển khai website, cửa hàng điện tử, source code Laravel, WordPress và hệ thống web theo yêu cầu.'
        );
        $keywords = array_values(array_filter(array_map(
            'trim',
            explode(',', Setting::getValue('seo_contact_keywords', 'liên hệ lập trình viên, thiết kế website, source code Laravel, WordPress, cửa hàng điện tử'))
        )));
        $canonicalUrl = route('contact.create');
        $siteName = Setting::getValue('site_name', 'NQT Dev');
        $image = Setting::getValue('seo_default_image', asset('logo.png'));

        SEOTools::setTitle($title);
        SEOTools::setDescription($description);
        SEOTools::metatags()->addKeyword($keywords);
        SEOTools::metatags()->setRobots('index, follow');
        SEOTools::setCanonical($canonicalUrl);
        SEOTools::opengraph()->setUrl($canonicalUrl);
        SEOTools::opengraph()->setType('website');
        SEOTools::opengraph()->setSiteName($siteName);
        SEOTools::twitter()->setType('summary_large_image');
        SEOTools::twitter()->setUrl($canonicalUrl);
        SEOTools::jsonLdMulti()->setType('ContactPage');
        SEOTools::jsonLdMulti()->setUrl($canonicalUrl);
        SEOTools::addImages($image);
    }
}
