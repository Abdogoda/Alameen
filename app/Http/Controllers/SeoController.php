<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Course;
use App\Services\Seo\SeoService;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SeoController extends Controller
{
    public function __construct(
        protected SeoService $seoService
    ) {}

    /**
     * Generate dynamic XML Sitemap for Google & search engines.
     */
    public function sitemap(): Response
    {
        if (! $this->seoService->isSitemapEnabled()) {
            abort(404);
        }

        $now = Carbon::now()->toAtomString();
        $urls = [];

        // 1. Homepage
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '1.0',
            'ar' => url('/?locale=ar'),
            'en' => url('/?locale=en'),
        ];

        // 2. Courses Directory
        $urls[] = [
            'loc' => url('/courses'),
            'lastmod' => $now,
            'changefreq' => 'daily',
            'priority' => '0.9',
            'ar' => url('/courses?locale=ar'),
            'en' => url('/courses?locale=en'),
        ];

        // 3. Dynamic Published Courses
        $courses = Course::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($courses as $course) {
            $courseUrl = route('courses.show', $course->slug ?: $course->id);
            $urls[] = [
                'loc' => $courseUrl,
                'lastmod' => $course->updated_at?->toAtomString() ?? $now,
                'changefreq' => 'weekly',
                'priority' => '0.9',
                'ar' => $courseUrl.'?locale=ar',
                'en' => $courseUrl.'?locale=en',
            ];
        }

        // 4. Student Reviews & Testimonials
        $urls[] = [
            'loc' => url('/reviews'),
            'lastmod' => $now,
            'changefreq' => 'weekly',
            'priority' => '0.8',
            'ar' => url('/reviews?locale=ar'),
            'en' => url('/reviews?locale=en'),
        ];

        // 5. Contact & Headquarters
        $urls[] = [
            'loc' => url('/contact'),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.7',
            'ar' => url('/contact?locale=ar'),
            'en' => url('/contact?locale=en'),
        ];

        // 6. Clinical Audit Report
        $urls[] = [
            'loc' => url('/audit-report'),
            'lastmod' => $now,
            'changefreq' => 'monthly',
            'priority' => '0.6',
            'ar' => url('/audit-report?locale=ar'),
            'en' => url('/audit-report?locale=en'),
        ];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'."\n";

        foreach ($urls as $item) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>'.htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8')."</loc>\n";
            $xml .= '    <lastmod>'.$item['lastmod']."</lastmod>\n";
            $xml .= '    <changefreq>'.$item['changefreq']."</changefreq>\n";
            $xml .= '    <priority>'.$item['priority']."</priority>\n";
            $xml .= '    <xhtml:link rel="alternate" hreflang="ar" href="'.htmlspecialchars($item['ar'], ENT_XML1, 'UTF-8').'" />'."\n";
            $xml .= '    <xhtml:link rel="alternate" hreflang="en" href="'.htmlspecialchars($item['en'], ENT_XML1, 'UTF-8').'" />'."\n";
            $xml .= '    <xhtml:link rel="alternate" hreflang="x-default" href="'.htmlspecialchars($item['loc'], ENT_XML1, 'UTF-8').'" />'."\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
            'X-Robots-Tag' => 'noindex, follow',
        ]);
    }

    /**
     * Dynamic Robots.txt handler.
     */
    public function robots(): Response
    {
        $content = $this->seoService->getRobotsTxt();

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
