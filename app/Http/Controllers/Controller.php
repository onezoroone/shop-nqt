<?php

namespace App\Http\Controllers;

use Artesaos\SEOTools\Facades\SEOTools;

abstract class Controller
{
    protected function configureNoIndexSeo(
        string $title,
        string $description,
        ?string $canonicalUrl = null,
        bool $followLinks = false
    ): void {
        SEOTools::metatags()->setTitle($title);
        SEOTools::metatags()->setDescription($description);
        SEOTools::metatags()->setRobots(
            $followLinks
                ? 'noindex, follow, noarchive'
                : 'noindex, nofollow, noarchive, nosnippet'
        );

        if ($canonicalUrl !== null) {
            SEOTools::setCanonical($canonicalUrl);
        }

        SEOTools::jsonLdMulti()->setType(false);
    }
}
