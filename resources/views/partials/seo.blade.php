@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'type' => null,
    'canonical' => null,
    'robots' => null,
    'course' => null,
])

@php
    $seo = app(\App\Services\Seo\SeoService::class);
    $seoTitle = $seo->getMetaTitle($title ?? null);
    $seoDesc = $seo->getMetaDescription($description ?? null);
    $seoKeywords = $seo->getMetaKeywords($keywords ?? null);
    $seoAuthor = $seo->getAuthor();
    $seoRobots = $seo->getRobotsDirective($robots ?? null);
    $canonicalUrl = $seo->getCanonicalUrl($canonical ?? null);
    $hreflangs = $seo->getHreflangUrls();
    $ogImage = $seo->getOgImage($image ?? null);
    $ogType = $seo->getOgType($type ?? null);
    $twitterCard = $seo->getTwitterCard();
    $twitterHandle = $seo->getTwitterHandle();
    $googleToken = $seo->getGoogleVerification();
    $bingToken = $seo->getBingVerification();
    $headScripts = $seo->getCustomHeadScripts();
    $structuredData = $seo->buildStructuredData($title ?? null, $course ?? null);
    $currentLocale = app()->getLocale();
@endphp

<!-- Primary SEO Meta Tags -->
<title>{{ $seoTitle }}</title>
<meta name="title" content="{{ $seoTitle }}">
<meta name="description" content="{{ $seoDesc }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="author" content="{{ $seoAuthor }}">
<meta name="robots" content="{{ $seoRobots }}">
<meta name="googlebot" content="{{ $seoRobots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<!-- Multilingual SEO & Alternate Links -->
<link rel="alternate" hreflang="ar" href="{{ $hreflangs['ar'] }}">
<link rel="alternate" hreflang="en" href="{{ $hreflangs['en'] }}">
<link rel="alternate" hreflang="x-default" href="{{ $hreflangs['x-default'] }}">

<!-- Open Graph / Facebook Meta Tags -->
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDesc }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:secure_url" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $seoTitle }}">
<meta property="og:site_name" content="{{ platform_name() }}">
<meta property="og:locale" content="{{ $currentLocale === 'en' ? 'en_US' : 'ar_AR' }}">
<meta property="og:locale:alternate" content="{{ $currentLocale === 'en' ? 'ar_AR' : 'en_US' }}">

<!-- Twitter Cards Meta Tags -->
<meta name="twitter:card" content="{{ $twitterCard }}">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDesc }}">
<meta name="twitter:image" content="{{ $ogImage }}">
<meta name="twitter:image:alt" content="{{ $seoTitle }}">
@if(!empty($twitterHandle))
<meta name="twitter:site" content="{{ $twitterHandle }}">
<meta name="twitter:creator" content="{{ $twitterHandle }}">
@endif

<!-- Search Engine Webmaster Verifications -->
@if(!empty($googleToken))
<meta name="google-site-verification" content="{{ $googleToken }}">
@endif
@if(!empty($bingToken))
<meta name="msvalidate.01" content="{{ $bingToken }}">
@endif

<!-- Schema.org JSON-LD Structured Data for Rich Snippets -->
@if(!empty($structuredData))
@foreach($structuredData as $schemaPayload)
<script type="application/ld+json">
{!! json_encode($schemaPayload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
</script>
@endforeach
@endif

<!-- Custom Admin Injected Head Analytics Scripts -->
@if(!empty($headScripts))
{!! $headScripts !!}
@endif
