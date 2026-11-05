<?php

declare(strict_types=1);

namespace App\Services\Cms;

use App\Models\ContentBlock;
use App\Models\Course;
use App\Models\MenuItem;
use App\Services\Localization\LocalizationService;
use App\Services\Seo\SeoService;

class PublicContentService
{
    public function __construct(
        protected SettingsService $settingsService,
        protected MenuService $menuService,
        protected LocalizationService $localizationService,
        protected SeoService $seoService
    ) {}

    /**
     * Get global application bootstrap configuration for Web & Mobile/SPA clients.
     *
     * @return array<string, mixed>
     */
    public function getBootstrapConfig(): array
    {
        $locale = $this->localizationService->getCurrentLocale();

        return [
            'branding' => [
                'site_name' => $this->settingsService->getSiteName(),
                'site_tagline' => $this->settingsService->getSiteTagline(),
                'logo_url' => $this->settingsService->getLogoUrl(),
                'logo_white_url' => $this->settingsService->getLogoWhiteUrl(),
                'footer_logo_url' => $this->settingsService->getFooterLogoUrl(),
                'auth_login_image_url' => $this->settingsService->getAuthLoginImageUrl(),
                'footer_badge' => $this->settingsService->getFooterBadge(),
                'certificate_bg' => $this->settingsService->getCertificateBg(),
            ],
            'currency' => [
                'symbol' => $this->settingsService->getCurrencySymbol(),
                'name' => $this->settingsService->getCurrencyName(),
            ],
            'theme' => [
                'primary_color' => $this->settingsService->getThemePrimaryColor(),
                'secondary_color' => $this->settingsService->getThemeSecondaryColor(),
                'accent_color' => $this->settingsService->getThemeAccentColor(),
                'gold_color' => $this->settingsService->getThemeGoldColor(),
                'footer_bg' => $this->settingsService->getThemeFooterBg(),
            ],
            'contact' => [
                'email' => $this->settingsService->getContactEmail(),
                'phone' => $this->settingsService->getContactPhone(),
                'whatsapp_url' => $this->settingsService->getWhatsAppUrl(),
                'address' => $this->settingsService->getHeadquartersAddress(),
                'working_hours' => $this->settingsService->getWorkingHours(),
                'support_status_online' => $this->settingsService->get('support_status_online', '1') === '1',
                'social' => [
                    'facebook_personal' => $this->settingsService->getPersonalFacebook(),
                    'facebook_center' => $this->settingsService->getCenterFacebook(),
                    'tiktok_personal' => $this->settingsService->getPersonalTikTok(),
                    'tiktok_center' => $this->settingsService->getCenterTikTok(),
                ],
            ],
            'features' => [
                'student_dashboard' => $this->settingsService->isPageEnabled('student_dashboard'),
                'courses' => $this->settingsService->isPageEnabled('courses'),
                'tasks' => $this->settingsService->isPageEnabled('tasks'),
                'grades' => $this->settingsService->isPageEnabled('grades'),
                'notifications' => $this->settingsService->isPageEnabled('notifications'),
                'settings' => $this->settingsService->isPageEnabled('settings'),
            ],
            'localization' => [
                'current_locale' => $locale,
                'direction' => $this->localizationService->getDirection($locale),
                'is_rtl' => $this->localizationService->isRtl($locale),
                'supported_locales' => $this->localizationService->getSupportedLocales(),
            ],
            'menus' => [
                'header' => $this->formatMenu($this->menuService->getMenu('header')),
                'footer_quick' => $this->formatMenu($this->menuService->getMenu('footer_quick')),
                'footer_support' => $this->formatMenu($this->menuService->getMenu('footer_support')),
                'footer_legal' => $this->formatMenu($this->menuService->getMenu('footer_legal')),
            ],
        ];
    }

    /**
     * Get complete landing page content (shared between welcome.blade.php and GET /api/v1/public/home).
     *
     * @return array<string, mixed>
     */
    public function getLandingPageData(): array
    {
        $heroBlock = ContentBlock::where('section_key', 'hero')->first();
        $feedbackBlock = ContentBlock::where('section_key', 'feedback')->first();
        $reviewsBlock = ContentBlock::where('section_key', 'reviews')->first();
        $aboutBlock = ContentBlock::where('section_key', 'about')->first();
        $faqBlock = ContentBlock::where('section_key', 'faq')->first();

        $heroMeta = $heroBlock?->metadata ?? [];
        $feedbackMeta = $feedbackBlock?->metadata ?? [];
        $loc = app()->getLocale();

        $cta1Text = $heroMeta['cta_primary_'.$loc]
            ?? ($loc === 'ar'
                ? ($heroMeta['cta_primary_ar'] ?? __('landing.slide1Cta1'))
                : ($heroMeta['cta_primary_en'] ?? __('landing.slide1Cta1')));
        $cta1Url = ! empty($heroMeta['cta_primary_url']) ? $heroMeta['cta_primary_url'] : '#courses';

        $cta2Text = $heroMeta['cta_secondary_'.$loc]
            ?? ($loc === 'ar'
                ? ($heroMeta['cta_secondary_ar'] ?? __('landing.slide1Cta2'))
                : ($heroMeta['cta_secondary_en'] ?? __('landing.slide1Cta2')));
        $cta2Url = ! empty($heroMeta['cta_secondary_url']) ? $heroMeta['cta_secondary_url'] : '#about';
        $studentsCount = $heroMeta['students_count'] ?? '2500+';

        $defaultHeroImages = [
            0 => '/assets/images/alameen-images/7.jpeg',
            1 => '/assets/images/alameen-images/1.mp4',
            2 => '/assets/images/alameen-images/2.mp4',
            3 => '/assets/images/alameen-images/8.jpeg',
        ];
        $legacyHeroDefaults = [
            0 => '/assets/images/alameen-images/6.jpeg',
            1 => '/assets/images/alameen-images/1.jpeg',
            2 => '/assets/images/alameen-images/2.jpeg',
            3 => '/assets/images/alameen-images/3.jpeg',
        ];
        $heroImages = [];
        for ($i = 0; $i < 4; $i++) {
            $val = $heroMeta['images'][$i] ?? null;
            if (
                empty($val) ||
                $val === ($legacyHeroDefaults[$i] ?? '') ||
                ($i === 1 && ! str_ends_with(strtolower((string) $val), '.mp4') && ! str_ends_with(strtolower((string) $val), '.webm')) ||
                ($i === 2 && ! str_ends_with(strtolower((string) $val), '.mp4') && ! str_ends_with(strtolower((string) $val), '.webm'))
            ) {
                $val = $defaultHeroImages[$i];
            }
            $heroImages[$i] = $val;
        }

        $defaultFeedbackImages = [
            0 => '/assets/images/alameen-images/6.jpeg',
            1 => '/assets/images/alameen-images/1.jpeg',
            2 => '/assets/images/alameen-images/2.jpeg',
            3 => '/assets/images/alameen-images/3.jpeg',
            4 => '/assets/images/alameen-images/9.jpeg',
            5 => '/assets/images/alameen-images/4.jpeg',
            6 => '/assets/images/alameen-images/5.jpeg',
            7 => '/assets/images/alameen-images/certificate.png',
            8 => '/assets/images/alameen-images/12.jpeg',
        ];
        $feedbackImages = [];
        for ($i = 0; $i < 9; $i++) {
            $val = $feedbackMeta['images'][$i] ?? null;
            if (empty($val) || str_contains((string) $val, '14.png')) {
                $val = $defaultFeedbackImages[$i];
            }
            if ($i === 0 && (empty($val) || ! str_contains((string) $val, '6.jpeg'))) {
                $val = '/assets/images/alameen-images/6.jpeg';
            }
            if ($i === 4 && (empty($val) || $val === '/assets/images/alameen-images/6.jpeg' || $val === '/assets/images/alameen-images/4.jpeg' || ! str_contains((string) $val, '9.jpeg'))) {
                $val = '/assets/images/alameen-images/9.jpeg';
            }
            if ($i === 7 && (empty($val) || str_contains((string) $val, '14.png'))) {
                $val = '/assets/images/alameen-images/certificate.png';
            }
            if ($i === 8 && (empty($val) || $val === '/assets/images/alameen-images/4.jpeg' || $val === '/assets/images/alameen-images/6.jpeg' || ! str_contains((string) $val, '12.jpeg'))) {
                $val = '/assets/images/alameen-images/12.jpeg';
            }
            $feedbackImages[$i] = $val;
        }

        $courses = Course::query()
            ->where('is_published', true)
            ->withCount(['videos' => fn ($q) => $q->published()])
            ->orderBy('sort_order')
            ->get();

        $rawPillars = $aboutBlock?->metadata['pillars'] ?? [];
        $pillars = [
            0 => [
                'title' => $rawPillars[0]['title_'.$loc] ?? __('landing.pillar1Title'),
                'description' => $rawPillars[0]['desc_'.$loc] ?? __('landing.pillar1Desc'),
                'title_ar' => $rawPillars[0]['title_ar'] ?? __('landing.pillar1Title'),
                'title_en' => $rawPillars[0]['title_en'] ?? __('landing.pillar1Title'),
                'desc_ar' => $rawPillars[0]['desc_ar'] ?? __('landing.pillar1Desc'),
                'desc_en' => $rawPillars[0]['desc_en'] ?? __('landing.pillar1Desc'),
            ],
            1 => [
                'title' => $rawPillars[1]['title_'.$loc] ?? __('landing.pillar2Title'),
                'description' => $rawPillars[1]['desc_'.$loc] ?? __('landing.pillar2Desc'),
                'title_ar' => $rawPillars[1]['title_ar'] ?? __('landing.pillar2Title'),
                'title_en' => $rawPillars[1]['title_en'] ?? __('landing.pillar2Title'),
                'desc_ar' => $rawPillars[1]['desc_ar'] ?? __('landing.pillar2Desc'),
                'desc_en' => $rawPillars[1]['desc_en'] ?? __('landing.pillar2Desc'),
            ],
            2 => [
                'title' => $rawPillars[2]['title_'.$loc] ?? __('landing.pillar3Title'),
                'description' => $rawPillars[2]['desc_'.$loc] ?? __('landing.pillar3Desc'),
                'title_ar' => $rawPillars[2]['title_ar'] ?? __('landing.pillar3Title'),
                'title_en' => $rawPillars[2]['title_en'] ?? __('landing.pillar3Title'),
                'desc_ar' => $rawPillars[2]['desc_ar'] ?? __('landing.pillar3Desc'),
                'desc_en' => $rawPillars[2]['desc_en'] ?? __('landing.pillar3Desc'),
            ],
            3 => [
                'title' => $rawPillars[3]['title_'.$loc] ?? __('landing.pillar4Title'),
                'description' => $rawPillars[3]['desc_'.$loc] ?? __('landing.pillar4Desc'),
                'title_ar' => $rawPillars[3]['title_ar'] ?? __('landing.pillar4Title'),
                'title_en' => $rawPillars[3]['title_en'] ?? __('landing.pillar4Title'),
                'desc_ar' => $rawPillars[3]['desc_ar'] ?? __('landing.pillar4Desc'),
                'desc_en' => $rawPillars[3]['desc_en'] ?? __('landing.pillar4Desc'),
            ],
        ];

        $reviewCards = $this->resolveReviewCards($reviewsBlock);

        $faqItems = [];
        if ($faqBlock && ! empty($faqBlock->metadata['items']) && is_array($faqBlock->metadata['items'])) {
            foreach ($faqBlock->metadata['items'] as $item) {
                $faqItems[] = [
                    'question' => ($loc === 'en' && ! empty($item['question_en']))
                        ? $item['question_en']
                        : ($item['question_ar'] ?? $item['q_'.$loc] ?? $item['q_ar'] ?? ''),
                    'answer' => ($loc === 'en' && ! empty($item['answer_en']))
                        ? $item['answer_en']
                        : ($item['answer_ar'] ?? $item['a_'.$loc] ?? $item['a_ar'] ?? ''),
                ];
            }
        }

        return [
            'heroBlock' => $heroBlock,
            'feedbackBlock' => $feedbackBlock,
            'reviewsBlock' => $reviewsBlock,
            'aboutBlock' => $aboutBlock,
            'faqBlock' => $faqBlock,
            'cmsSettings' => $this->settingsService,
            'loc' => $loc,
            'cta1Text' => $cta1Text,
            'cta1Url' => $cta1Url,
            'cta2Text' => $cta2Text,
            'cta2Url' => $cta2Url,
            'studentsCount' => $studentsCount,
            'heroImages' => $heroImages,
            'feedbackImages' => $feedbackImages,
            'courses' => $courses,
            'pillars' => $pillars,
            'reviewCards' => $reviewCards,
            'faqItems' => $faqItems,
        ];
    }

    /**
     * Get structured reviews & rating breakdown data for Web & API.
     *
     * @return array<string, mixed>
     */
    public function getReviewsPageData(): array
    {
        $reviewsBlock = ContentBlock::where('section_key', 'reviews')->first();
        $feedbackBlock = ContentBlock::where('section_key', 'feedback')->first();
        $landing = $this->getLandingPageData();

        return [
            'hero' => [
                'eyebrow' => __('reviews.eyebrow'),
                'title' => $reviewsBlock?->title ?: __('reviews.heroTitle'),
                'description' => $reviewsBlock?->subtitle ?: __('reviews.heroDesc'),
            ],
            'stats' => [
                'average_rating' => 4.9,
                'rating_label' => __('reviews.statRating'),
                'rating_sub' => __('reviews.statRatingSub'),
                'total_reviews' => __('reviews.statReviews'),
                'total_reviews_sub' => __('reviews.statReviewsSub'),
                'satisfaction_rate' => __('reviews.statSatisfaction'),
                'satisfaction_sub' => __('reviews.statSatisfactionSub'),
                'verified_graduates' => __('reviews.statVerified'),
                'verified_sub' => __('reviews.statVerifiedSub'),
                'breakdown' => [
                    '5_stars' => 88,
                    '4_stars' => 9,
                    '3_stars' => 2,
                    '2_stars' => 1,
                    '1_star' => 0,
                ],
            ],
            'reviews' => $this->resolveReviewCards($reviewsBlock),
            'feedback_gallery' => array_values($landing['feedbackImages']),
            'feedback_block' => $feedbackBlock ? [
                'title' => $feedbackBlock->title,
                'subtitle' => $feedbackBlock->subtitle,
            ] : null,
        ];
    }

    /**
     * Get structured contact & support availability data for Web & API.
     *
     * @return array<string, mixed>
     */
    public function getContactPageData(): array
    {
        $contactEmail = $this->settingsService->get('contact_email', 'contact@alameen-speech.com') ?: $this->settingsService->getContactEmail();
        $contactPhone = $this->settingsService->get('contact_phone', '+20 100 000 0000') ?: $this->settingsService->getContactPhone();
        $whatsappUrl = $this->settingsService->get('social_whatsapp', 'https://wa.me/201000000000') ?: $this->settingsService->getWhatsAppUrl();
        $contactAddress = $this->settingsService->get('contact_address', 'القاهرة، جمهورية مصر العربية') ?: $this->settingsService->getHeadquartersAddress();
        $workingHours = $this->settingsService->get('contact_working_hours', 'السبت - الخميس: 9:00 ص - 9:00 م') ?: $this->settingsService->getWorkingHours();
        $supportStatusOnline = $this->settingsService->get('support_status_online', '1') === '1';

        return [
            'contactEmail' => $contactEmail,
            'contactPhone' => $contactPhone,
            'whatsappUrl' => $whatsappUrl,
            'contactAddress' => $contactAddress,
            'workingHours' => $workingHours,
            'supportStatusOnline' => $supportStatusOnline,
            'social' => [
                'facebook_personal' => $this->settingsService->getPersonalFacebook(),
                'facebook_center' => $this->settingsService->getCenterFacebook(),
                'tiktok_personal' => $this->settingsService->getPersonalTikTok(),
                'tiktok_center' => $this->settingsService->getCenterTikTok(),
            ],
            'topics' => [
                ['key' => 'general', 'label' => __('contact.topicGeneral')],
                ['key' => 'technical', 'label' => __('contact.topicTechnical')],
                ['key' => 'certificate', 'label' => __('contact.topicCertificate')],
            ],
        ];
    }

    /**
     * Get SEO metadata and Schema.org JSON-LD payload.
     *
     * @return array<string, mixed>
     */
    public function getSeoData(?string $pageTitle = null, ?Course $course = null): array
    {
        return [
            'title' => $this->seoService->getMetaTitle($pageTitle),
            'description' => $this->seoService->getMetaDescription(),
            'keywords' => $this->seoService->getMetaKeywords(),
            'author' => $this->seoService->getAuthor(),
            'robots' => $this->seoService->getRobotsDirective(),
            'canonical_url' => $this->seoService->getCanonicalUrl(),
            'hreflang' => $this->seoService->getHreflangUrls(),
            'og_image' => $this->seoService->getOgImage(),
            'og_type' => $this->seoService->getOgType(),
            'twitter_card' => $this->seoService->getTwitterCard(),
            'twitter_handle' => $this->seoService->getTwitterHandle(),
            'structured_data' => $this->seoService->buildStructuredData($pageTitle, $course),
        ];
    }

    /**
     * Resolve localized review cards with defaults.
     *
     * @return array<int, array<string, string>>
     */
    protected function resolveReviewCards(?ContentBlock $reviewsBlock): array
    {
        $defaultReviewCards = [
            0 => [
                'author' => __('reviews.testimonial1Author'),
                'role' => __('reviews.testimonial1Role'),
                'badge' => __('reviews.badgeCertifiedStudent'),
                'title' => __('reviews.testimonial1Title'),
                'text' => __('reviews.testimonial1Text'),
                'image' => '/assets/images/alameen-images/2.jpeg',
                'initial' => 'أ',
                'delay' => '0ms',
            ],
            1 => [
                'author' => __('reviews.testimonial2Author'),
                'role' => __('reviews.testimonial2Role'),
                'badge' => __('reviews.badgeCertifiedSpecialist'),
                'title' => __('reviews.testimonial2Title'),
                'text' => __('reviews.testimonial2Text'),
                'image' => '/assets/images/alameen-images/1.jpeg',
                'initial' => 'خ',
                'delay' => '150ms',
            ],
            2 => [
                'author' => __('reviews.testimonial3Author'),
                'role' => __('reviews.testimonial3Role'),
                'badge' => __('reviews.badgeCertifiedStudent'),
                'title' => __('reviews.testimonial3Title'),
                'text' => __('reviews.testimonial3Text'),
                'image' => '/assets/images/alameen-images/3.jpeg',
                'initial' => 'ف',
                'delay' => '300ms',
            ],
        ];

        $savedReviews = $reviewsBlock?->metadata['items'] ?? [];
        $reviewCards = [];

        for ($k = 0; $k < 3; $k++) {
            $saved = $savedReviews[$k] ?? [];
            $def = $defaultReviewCards[$k];
            $author = ! empty($saved['author']) ? (string) $saved['author'] : (string) $def['author'];

            $reviewCards[$k] = [
                'author' => $author,
                'role' => ! empty($saved['role']) ? (string) $saved['role'] : (string) $def['role'],
                'badge' => ! empty($saved['badge']) ? (string) $saved['badge'] : (string) $def['badge'],
                'title' => ! empty($saved['title']) ? (string) $saved['title'] : (string) $def['title'],
                'text' => ! empty($saved['text']) ? (string) $saved['text'] : (string) $def['text'],
                'image' => ! empty($saved['image']) ? (string) $saved['image'] : (string) $def['image'],
                'initial' => mb_substr($author, 0, 1),
                'delay' => $def['delay'],
            ];
        }

        return $reviewCards;
    }

    /**
     * Format menu collection into clean array for API consumption.
     *
     * @param  \Illuminate\Database\Eloquent\Collection<int, MenuItem>  $items
     * @return list<array<string, mixed>>
     */
    protected function formatMenu($items): array
    {
        return $items->map(fn (MenuItem $item) => [
            'id' => $item->id,
            'title' => app()->getLocale() === 'en' && ! empty($item->title_en) ? $item->title_en : $item->title_ar,
            'title_ar' => $item->title_ar,
            'title_en' => $item->title_en,
            'url' => $item->url,
            'route_name' => $item->route_name,
            'target' => $item->target,
            'sort_order' => $item->sort_order,
        ])->values()->all();
    }
}

