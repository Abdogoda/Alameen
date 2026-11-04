@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'type' => null,
    'canonical' => null,
    'robots' => null,
    'course' => null,
    'active' => 'home',
    'modals' => null,
    'scripts' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
    <head>
        @include('partials.head', [
            'title' => $title ?? null,
            'description' => $description ?? null,
            'keywords' => $keywords ?? null,
            'image' => $image ?? null,
            'type' => $type ?? null,
            'canonical' => $canonical ?? null,
            'robots' => $robots ?? null,
            'course' => $course ?? null,
        ])
    </head>
    <body class="bg-[#FAFCFF] dark:bg-slate-950 min-h-screen text-[#334155] dark:text-slate-100 font-body antialiased selection:bg-[#EAF1FB] selection:text-[#0B2A8F] w-full max-w-full relative transition-colors">
        {!! app(\App\Services\Seo\SeoService::class)->getCustomBodyScripts() !!}
        
        <!-- Top-Level Pre-Rendered Themed Loader (At the top of everything from 0ms) -->
        @persist('alameen-loader')
        <x-alameen-loader />
        @endpersist

        <!-- Toast Notification Element -->
        <div
            id="toast"
            class="fixed top-6 end-6 z-[9999] bg-[#012383] text-white px-5 py-3.5 rounded-2xl shadow-2xl flex items-center gap-3 transform -translate-y-20 opacity-0 transition-all duration-300 pointer-events-none"
        >
            <svg class="size-5 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span id="toast-message" class="text-sm font-semibold"></span>
        </div>

        <!-- Main Full-Width Canvas Container with Smooth Reveal -->
        <div class="w-full min-h-screen flex flex-col relative bg-[#FAFCFF] dark:bg-slate-950 transition-colors alameen-content-wrap" id="landing-frame">
            
            <!-- Master Marketing Header -->
            <x-marketing-header :active="$active" />

            <!-- Main Content Area -->
            <main class="flex-1 flex flex-col">
                {{ $slot }}
            </main>

            <!-- Master Marketing Footer -->
            <x-marketing-footer />

        </div>

        {{ $modals ?? '' }}

        <!-- Review Lightbox Core Handlers (Available Globally Across All Marketing Pages) -->
        <script>
            window.openReviewImageLightbox = function (src, author) {
                const lightbox = document.getElementById('review-image-lightbox');
                const img = document.getElementById('review-lightbox-img');
                const caption = document.getElementById('review-lightbox-caption');
                if (!lightbox || !img) return;

                img.src = src;
                if (caption) {
                    const isEn = document.documentElement.lang === 'en';
                    caption.textContent = author
                        ? (isEn ? `Photo attached by ${author}` : `صورة مرفقة مع تقييم ${author}`)
                        : (isEn ? 'Attached review photo' : 'صورة مرفقة مع التقييم');
                }
                lightbox.classList.remove('opacity-0', 'pointer-events-none');
                lightbox.classList.add('opacity-100', 'pointer-events-auto');
                document.body.classList.add('overflow-hidden');
            };

            window.closeReviewImageLightbox = function () {
                const lightbox = document.getElementById('review-image-lightbox');
                const img = document.getElementById('review-lightbox-img');
                if (!lightbox) return;

                lightbox.classList.add('opacity-0', 'pointer-events-none');
                lightbox.classList.remove('opacity-100', 'pointer-events-auto');
                document.body.classList.remove('overflow-hidden');
                if (img) {
                    setTimeout(() => {
                        if (lightbox.classList.contains('opacity-0')) {
                            img.src = '';
                        }
                    }, 300);
                }
            };

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && window.closeReviewImageLightbox) {
                    window.closeReviewImageLightbox();
                }
            });
        </script>

        <!-- Landing & Animation Scripts -->
        @if(($active ?? '') === 'home')
        <script type="module" src="/src/js/landing.js"></script>
        @endif
        <script type="module" src="/src/js/animations.js"></script>
        {{ $scripts ?? '' }}

        @fluxScripts

        @if(app()->getLocale() === 'en')
        <script>
        window.__I18N_DICTIONARY__ = {
            courses: @json(__('courses')),
            lesson: @json(__('lesson')),
            grades: @json(__('grades')),
            result: @json(__('result')),
            profile: @json(__('profile')),
            catalog: @json(__('catalog')),
            reviews: @json(__('reviews')),
            contact: @json(__('contact')),
            landing: @json(__('landing')),
        };
        window.__EN_STRINGS__ = @json(json_decode(file_get_contents(base_path('lang/en.json')), true) ?: []);

        function stripTashkeel(s) {
            return s ? s.replace(/[\u064B-\u065F\u0670\u06D6-\u06ED]/g, '').trim() : '';
        }

        const rawTrans = window.__EN_STRINGS__ || {};
        const normTrans = {};
        for (const [k, v] of Object.entries(rawTrans)) {
            const tk = k.trim();
            normTrans[tk] = v;
            const stripped = stripTashkeel(tk);
            if (stripped && !normTrans[stripped]) {
                normTrans[stripped] = v;
            }
        }

        function applyI18nTranslations(root = document) {
            if (!root) return;
            const dict = window.__I18N_DICTIONARY__;
            if (dict) {
                root.querySelectorAll('[data-i18n]').forEach(el => {
                    const keyPath = el.getAttribute('data-i18n');
                    if (!keyPath) return;
                    const [group, key] = keyPath.split('.');
                    if (dict[group] && dict[group][key]) {
                        el.textContent = dict[group][key];
                    }
                });
                root.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
                    const keyPath = el.getAttribute('data-i18n-placeholder');
                    if (!keyPath) return;
                    const [group, key] = keyPath.split('.');
                    if (dict[group] && dict[group][key]) {
                        el.setAttribute('placeholder', dict[group][key]);
                    }
                });
                root.querySelectorAll('[data-i18n-title]').forEach(el => {
                    const keyPath = el.getAttribute('data-i18n-title');
                    if (!keyPath) return;
                    const [group, key] = keyPath.split('.');
                    if (dict[group] && dict[group][key]) {
                        el.setAttribute('title', dict[group][key]);
                    }
                });
            }

            root.querySelectorAll('[placeholder], [title], [aria-label]').forEach(el => {
                const ph = el.getAttribute('placeholder');
                if (ph) {
                    const t = ph.trim();
                    if (normTrans[t]) el.setAttribute('placeholder', normTrans[t]);
                    else if (normTrans[stripTashkeel(t)]) el.setAttribute('placeholder', normTrans[stripTashkeel(t)]);
                }
                const tt = el.getAttribute('title');
                if (tt) {
                    const t = tt.trim();
                    if (normTrans[t]) el.setAttribute('title', normTrans[t]);
                    else if (normTrans[stripTashkeel(t)]) el.setAttribute('title', normTrans[stripTashkeel(t)]);
                }
                const al = el.getAttribute('aria-label');
                if (al) {
                    const t = al.trim();
                    if (normTrans[t]) el.setAttribute('aria-label', normTrans[t]);
                    else if (normTrans[stripTashkeel(t)]) el.setAttribute('aria-label', normTrans[stripTashkeel(t)]);
                }
            });

            const targetRoot = root.body || root;
            const walker = document.createTreeWalker(targetRoot, NodeFilter.SHOW_TEXT, {
                acceptNode(node) {
                    if (!node.nodeValue || !node.nodeValue.trim()) return NodeFilter.FILTER_REJECT;
                    const parent = node.parentElement;
                    if (!parent) return NodeFilter.FILTER_REJECT;
                    const tag = parent.tagName.toLowerCase();
                    if (tag === 'script' || tag === 'style' || tag === 'code' || tag === 'pre') return NodeFilter.FILTER_REJECT;
                    return NodeFilter.FILTER_ACCEPT;
                }
            }, false);

            let node;
            while ((node = walker.nextNode())) {
                const original = node.nodeValue;
                const trimmed = original.trim();
                if (!trimmed) continue;

                if (normTrans[trimmed]) {
                    node.nodeValue = original.replace(trimmed, normTrans[trimmed]);
                    continue;
                }
                const stripped = stripTashkeel(trimmed);
                if (stripped && normTrans[stripped]) {
                    node.nodeValue = original.replace(trimmed, normTrans[stripped]);
                    continue;
                }
                for (const arKey in normTrans) {
                    if (arKey.length >= 4 && original.includes(arKey)) {
                        node.nodeValue = node.nodeValue.replaceAll(arKey, normTrans[arKey]);
                    }
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => applyI18nTranslations());
        document.addEventListener('livewire:navigated', () => applyI18nTranslations());
        if (document.readyState !== 'loading') { applyI18nTranslations(); }
        </script>
        @endif
    </body>
</html>
