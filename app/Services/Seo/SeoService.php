<?php

declare(strict_types=1);

namespace App\Services\Seo;

use App\Models\ContentBlock;
use App\Models\Course;
use App\Services\Cms\SettingsService;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SeoService
{
    public function __construct(
        protected SettingsService $settings
    ) {}

    /**
     * Resolve the active route identifier for per-page SEO lookup.
     */
    public function resolvePageKey(): string
    {
        $routeName = Route::currentRouteName() ?? '';

        return match ($routeName) {
            'home', 'web.welcome' => 'home',
            'courses.index', 'courses', 'courses.show' => 'courses',
            'reviews.index', 'reviews' => 'reviews',
            'contact' => 'contact',
            'audit.report', 'audit' => 'audit',
            'certificates.verify' => 'verify_certificate',
            default => '',
        };
    }

    /**
     * Get the dynamic localized meta title.
     */
    public function getMetaTitle(?string $pageTitle = null): string
    {
        $siteName = $this->settings->getSiteName();
        $pageKey = $this->resolvePageKey();

        // 1. Direct page override from admin settings
        if (! empty($pageKey)) {
            $customPageTitle = $this->settings->get("seo_page_{$pageKey}_title");
            if (! empty($customPageTitle)) {
                return $customPageTitle;
            }
        }

        // 2. Global title template
        $globalTitle = $this->settings->get('seo_meta_title');
        if (empty($pageTitle)) {
            return $globalTitle ?: ($siteName.' | '.__('كورس التخاطب الشامل مع هنا المكاوي'));
        }

        // Clean page title from redundant site name suffix if already present
        $cleanTitle = trim(preg_replace('/(\s*\|\s*|\s*-\s*)'.preg_quote($siteName, '/').'$/i', '', $pageTitle));

        return "{$cleanTitle} | {$siteName}";
    }

    /**
     * Get the dynamic localized meta description.
     */
    public function getMetaDescription(?string $default = null): string
    {
        $pageKey = $this->resolvePageKey();

        if (! empty($pageKey)) {
            $pageDesc = $this->settings->get("seo_page_{$pageKey}_description");
            if (! empty($pageDesc)) {
                return Str::limit(trim(strip_tags($pageDesc)), 165);
            }
        }

        $globalDesc = $this->settings->get('seo_meta_description');
        if (! empty($globalDesc)) {
            return Str::limit(trim(strip_tags($globalDesc)), 165);
        }

        if (! empty($default)) {
            return Str::limit(trim(strip_tags($default)), 165);
        }

        $tagline = $this->settings->getSiteTagline();

        return Str::limit(trim(strip_tags($tagline)), 165);
    }

    /**
     * Get the dynamic localized meta keywords.
     */
    public function getMetaKeywords(?string $default = null): string
    {
        $pageKey = $this->resolvePageKey();

        if (! empty($pageKey)) {
            $pageKeywords = $this->settings->get("seo_page_{$pageKey}_keywords");
            if (! empty($pageKeywords)) {
                return trim($pageKeywords);
            }
        }

        $globalKeywords = $this->settings->get('seo_meta_keywords');
        if (! empty($globalKeywords)) {
            return trim($globalKeywords);
        }

        if (! empty($default)) {
            return trim($default);
        }

        return (app()->getLocale() === 'en')
            ? 'speech therapy, Hana Mekawy, speech rehabilitation, Al-Ameen Center, speech pathology courses, articulation disorders, stuttering therapy, accredited clinical certification'
            : 'تخاطب, دورات تخاطب, كورس التخاطب الشامل, هنا المكاوي, مركز الأمين, أخصائي نطق, تدريب تخاطب, علاج التلعثم, لدغات الحروف, تأخر نمو اللغة, توحد, شهادات معتمدة تخاطب';
    }

    /**
     * Get the author metadata.
     */
    public function getAuthor(): string
    {
        return $this->settings->get('seo_author', 'مركز الأمين - أ.د. هناء المكاوي') ?: 'مركز الأمين - أ.د. هناء المكاوي';
    }

    /**
     * Get the robots directive.
     */
    public function getRobotsDirective(?string $override = null): string
    {
        if ($override !== null) {
            return $override;
        }

        // Automatic safety: never index private auth or admin routes
        $route = Route::currentRouteName() ?? '';
        if (
            str_starts_with($route, 'admin.') ||
            str_starts_with($route, 'dashboard') ||
            str_starts_with($route, 'lessons.') ||
            str_starts_with($route, 'assignments.') ||
            str_starts_with($route, 'grades.') ||
            str_starts_with($route, 'results.') ||
            str_starts_with($route, 'user.') ||
            str_starts_with($route, 'settings.')
        ) {
            return 'noindex, nofollow, noarchive';
        }

        return $this->settings->get('seo_robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1')
            ?: 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1';
    }

    /**
     * Get the fully qualified Canonical URL.
     */
    public function getCanonicalUrl(?string $custom = null): string
    {
        if (! empty($custom)) {
            return $custom;
        }

        $canonicalBase = rtrim((string) $this->settings->get('seo_canonical_base_url'), '/');
        $currentPath = Request::path();
        $path = ($currentPath === '/' || empty($currentPath)) ? '' : '/'.ltrim($currentPath, '/');

        if (! empty($canonicalBase)) {
            return $canonicalBase.$path;
        }

        return url($path);
    }

    /**
     * Get Hreflang alternate URLs for multilingual SEO.
     *
     * @return array<string, string>
     */
    public function getHreflangUrls(): array
    {
        $currentUrl = Request::url();

        return [
            'ar' => url('locale/ar?redirect='.urlencode($currentUrl)),
            'en' => url('locale/en?redirect='.urlencode($currentUrl)),
            'x-default' => url($currentUrl),
        ];
    }

    /**
     * Get the OpenGraph Social Sharing Image.
     */
    public function getOgImage(?string $custom = null): string
    {
        if (! empty($custom)) {
            return $this->ensureAbsoluteUrl($custom);
        }

        $pageKey = $this->resolvePageKey();
        if (! empty($pageKey)) {
            $pageImg = $this->settings->get("seo_page_{$pageKey}_og_image");
            if (! empty($pageImg)) {
                return $this->ensureAbsoluteUrl($pageImg);
            }
        }

        $defaultOg = $this->settings->get('seo_og_image');
        if (! empty($defaultOg)) {
            return $this->ensureAbsoluteUrl($defaultOg);
        }

        return $this->ensureAbsoluteUrl('/assets/images/alameen-images/6.jpeg');
    }

    /**
     * Get OpenGraph Type (website, article, course).
     */
    public function getOgType(?string $override = null): string
    {
        if ($override !== null) {
            return $override;
        }

        return $this->settings->get('seo_og_type', 'website') ?: 'website';
    }

    /**
     * Get Twitter Card Type.
     */
    public function getTwitterCard(): string
    {
        return $this->settings->get('seo_twitter_card', 'summary_large_image') ?: 'summary_large_image';
    }

    /**
     * Get Twitter Site Handle.
     */
    public function getTwitterHandle(): string
    {
        return $this->settings->get('seo_twitter_handle', '@alameencenter') ?: '@alameencenter';
    }

    /**
     * Get Google Site Verification Token.
     */
    public function getGoogleVerification(): ?string
    {
        $token = $this->settings->get('seo_google_verification');

        if (empty($token)) {
            return null;
        }

        // If the user pasted the whole meta tag: <meta name="google-site-verification" content="..." />
        if (preg_match('/content=[\'"]([^\'"]+)[\'"]/i', $token, $matches)) {
            return $matches[1];
        }

        return trim($token);
    }

    /**
     * Get Bing Webmaster Verification Token.
     */
    public function getBingVerification(): ?string
    {
        $token = $this->settings->get('seo_bing_verification');

        if (empty($token)) {
            return null;
        }

        if (preg_match('/content=[\'"]([^\'"]+)[\'"]/i', $token, $matches)) {
            return $matches[1];
        }

        return trim($token);
    }

    /**
     * Get custom injection scripts for <head>.
     */
    public function getCustomHeadScripts(): ?string
    {
        return $this->settings->get('seo_head_custom_scripts');
    }

    /**
     * Get custom injection scripts for <body> (e.g. noscript).
     */
    public function getCustomBodyScripts(): ?string
    {
        return $this->settings->get('seo_body_custom_scripts');
    }

    /**
     * Check if XML Sitemap is enabled.
     */
    public function isSitemapEnabled(): bool
    {
        $val = $this->settings->get('seo_sitemap_enabled', '1');

        return $val === '1' || $val === 'true';
    }

    /**
     * Check if Structured Data (Schema.org) is enabled.
     */
    public function isSchemaOrgEnabled(): bool
    {
        $val = $this->settings->get('seo_schema_org_enabled', '1');

        return $val === '1' || $val === 'true';
    }

    /**
     * Build rich Schema.org JSON-LD Structured Data payload.
     *
     * @return list<array<string, mixed>>
     */
    public function buildStructuredData(?string $pageTitle = null, ?Course $course = null): array
    {
        if (! $this->isSchemaOrgEnabled()) {
            return [];
        }

        $siteName = $this->settings->getSiteName();
        $siteUrl = url('/');
        $logoUrl = $this->ensureAbsoluteUrl($this->settings->getLogoUrl());
        $phone = $this->settings->getContactPhone();
        $email = $this->settings->getContactEmail();
        $address = $this->settings->getHeadquartersAddress();

        // 1. Organization / EducationalOrganization Schema
        $organizationSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'EducationalOrganization',
            '@id' => "{$siteUrl}/#organization",
            'name' => $this->settings->get('seo_schema_org_name', $siteName) ?: $siteName,
            'alternateName' => [
                'Al-Ameen Center',
                'مركز الأمين',
                'منصة مركز الأمين للتخاطب',
            ],
            'url' => $siteUrl,
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logoUrl,
            ],
            'description' => $this->getMetaDescription(),
            'telephone' => $phone,
            'email' => $email,
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressLocality' => 'Giza',
                'addressRegion' => 'Giza',
                'addressCountry' => 'EG',
            ],
            'sameAs' => array_values(array_filter([
                $this->settings->getPersonalFacebook(),
                $this->settings->getCenterFacebook(),
                $this->settings->getPersonalTikTok(),
                $this->settings->getCenterTikTok(),
            ])),
        ];

        // 2. WebSite Schema with Sitelinks Searchbox
        $webSiteSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => "{$siteUrl}/#website",
            'url' => $siteUrl,
            'name' => $siteName,
            'publisher' => [
                '@id' => "{$siteUrl}/#organization",
            ],
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => "{$siteUrl}/courses?q={search_term_string}",
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];

        // 3. BreadcrumbList Schema
        $breadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => __('الرئيسية'),
                    'item' => $siteUrl,
                ],
            ],
        ];

        $pageKey = $this->resolvePageKey();
        if ($pageKey === 'courses') {
            $breadcrumbSchema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => __('الدورات والمسارات التدريبية'),
                'item' => url('/courses'),
            ];
        } elseif ($pageKey === 'reviews') {
            $breadcrumbSchema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => __('آراء المتدربين والتقييمات'),
                'item' => url('/reviews'),
            ];
        } elseif ($pageKey === 'contact') {
            $breadcrumbSchema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => __('تواصل معنا'),
                'item' => url('/contact'),
            ];
        }

        $schemas = [$organizationSchema, $webSiteSchema, $breadcrumbSchema];

        // 4. FAQPage Schema (if on home page and FAQ content block exists)
        if ($pageKey === 'home') {
            $faqBlock = ContentBlock::where('section_key', 'faq')->first();
            if ($faqBlock && ! empty($faqBlock->metadata['items']) && is_array($faqBlock->metadata['items'])) {
                $faqQuestions = [];
                $loc = app()->getLocale();
                foreach ($faqBlock->metadata['items'] as $item) {
                    $q = $item['q_'.$loc] ?? $item['q_ar'] ?? null;
                    $a = $item['a_'.$loc] ?? $item['a_ar'] ?? null;
                    if (! empty($q) && ! empty($a)) {
                        $faqQuestions[] = [
                            '@type' => 'Question',
                            'name' => trim(strip_tags((string) $q)),
                            'acceptedAnswer' => [
                                '@type' => 'Answer',
                                'text' => trim(strip_tags((string) $a)),
                            ],
                        ];
                    }
                }

                if (! empty($faqQuestions)) {
                    $schemas[] = [
                        '@context' => 'https://schema.org',
                        '@type' => 'FAQPage',
                        'mainEntity' => $faqQuestions,
                    ];
                }
            }
        }

        // 5. Course Schema (when viewing course details or on home/courses with primary course)
        $courseToSchema = $course ?: Course::where('is_published', true)->orderBy('sort_order')->first();
        if ($courseToSchema && ($pageKey === 'home' || $pageKey === 'courses' || $course !== null)) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'Course',
                'name' => $courseToSchema->title,
                'description' => $courseToSchema->description ?: $this->getMetaDescription(),
                'provider' => [
                    '@type' => 'EducationalOrganization',
                    'name' => $siteName,
                    'sameAs' => $siteUrl,
                ],
                'offers' => [
                    '@type' => 'Offer',
                    'price' => (string) $courseToSchema->price,
                    'priceCurrency' => 'EGP',
                    'availability' => 'https://schema.org/InStock',
                ],
                'hasCourseInstance' => [
                    '@type' => 'CourseInstance',
                    'courseMode' => 'Online',
                    'courseWorkload' => 'PT40H',
                    'instructor' => [
                        '@type' => 'Person',
                        'name' => 'أ.د. هناء المكاوي',
                        'jobTitle' => 'استشاري التخاطب وعيوب النطق والتأهيل السريري',
                    ],
                ],
                'educationalCredentialAwarded' => 'شهادة معتمدة وموثقة برمز QR من مركز الأمين للتدريب والتأهيل التخاطبي',
            ];
        }

        return $schemas;
    }

    /**
     * Default robots.txt content generator.
     */
    public function getDefaultRobotsTxt(): string
    {
        $appUrl = rtrim(config('app.url', url('/')), '/');

        return <<<TXT
# =========================================================================
# Al-Ameen Speech Center - Robots.txt Rules for Search Engine Crawlers
# =========================================================================
User-agent: *
Allow: /
Allow: /assets/
Allow: /build/
Allow: /src/

# Prevent crawlers from accessing private student LMS and admin endpoints
Disallow: /admin/
Disallow: /dashboard
Disallow: /lesson/
Disallow: /assignment/
Disallow: /grades
Disallow: /result
Disallow: /user-profile
Disallow: /storage/videos/lessons/
Disallow: /webhooks/

# Dynamic XML Sitemap Index
Sitemap: {$appUrl}/sitemap.xml
TXT;
    }

    /**
     * Retrieve the active robots.txt content.
     */
    public function getRobotsTxt(): string
    {
        $custom = $this->settings->get('seo_robots_txt');

        if (! empty($custom)) {
            return $custom;
        }

        return $this->getDefaultRobotsTxt();
    }

    /**
     * Helper to guarantee absolute URLs for metadata and schema.
     */
    protected function ensureAbsoluteUrl(string $url): string
    {
        $trimmed = trim($url);

        if (str_starts_with($trimmed, 'http://') || str_starts_with($trimmed, 'https://')) {
            return $trimmed;
        }

        return url('/'.ltrim($trimmed, '/'));
    }
}
