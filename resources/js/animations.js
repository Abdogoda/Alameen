/**
 * AL-AMEEN PLATFORM - UNIVERSAL ANIMATION & MOTION CONTROLLER
 * 
 * Provides smooth, high-performance, accessible animations across the entire system:
 * 1. ON REFRESH & LOAD:
 *    - Themed Alameen branded loader dissolution
 *    - Smooth page entrance fade & slide-up (alameen-page-transition-enter)
 *    - Cascading staggered entrance for viewport cards and sections
 * 2. ON SCROLL:
 *    - Dynamic top scroll progress bar (#alameen-scroll-progress)
 *    - IntersectionObserver-powered scroll reveals (fade, slide, zoom, stagger)
 *    - Sticky header backdrop-blur & elevation shadow on scroll (is-scrolled)
 *    - Floating spring-animated "Back to Top" button (#alameen-back-to-top)
 * 3. ON NAVIGATE (Livewire wire:navigate & SPA transitions):
 *    - Sleek glowing top route navigation progress bar (#alameen-nav-progress)
 *    - Gentle page exit dimming (alameen-navigating-out)
 *    - Page entrance transition on new view (alameen-page-transition-enter)
 *    - Automatic DOM re-observation for scroll reveals & micro-interactions
 *    - Smooth scroll reset & progress bar sync
 */

(function () {
    'use strict';

    // Prevent duplicate initialization
    if (typeof window !== 'undefined' && window.__alameenAnimationsLoaded) {
        return;
    }
    if (typeof window !== 'undefined') {
        window.__alameenAnimationsLoaded = true;
    }

    // =========================================================================
    // 1. TOP ROUTE NAVIGATION PROGRESS BAR (livewire:navigating & page switch)
    // =========================================================================
    let navProgressBar = null;
    let navProgressInterval = null;
    let navProgressResetTimer = null;
    let currentNavPercent = 0;

    function getOrCreateNavProgressBar() {
        if (navProgressBar && document.body.contains(navProgressBar)) {
            return navProgressBar;
        }

        navProgressBar = document.getElementById('alameen-nav-progress');
        if (!navProgressBar) {
            navProgressBar = document.createElement('div');
            navProgressBar.id = 'alameen-nav-progress';
            navProgressBar.setAttribute('aria-hidden', 'true');
            if (document.body) {
                document.body.prepend(navProgressBar);
            }
        }
        return navProgressBar;
    }

    const navProgress = {
        start() {
            const bar = getOrCreateNavProgressBar();
            if (!bar) return;

            clearInterval(navProgressInterval);
            clearTimeout(navProgressResetTimer);

            currentNavPercent = 18;
            bar.style.transition = 'width 0.15s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.2s ease';
            bar.style.opacity = '1';
            bar.style.width = currentNavPercent + '%';

            // Indicate navigation state to document
            document.body.classList.add('alameen-navigating-out');

            // Realistic network progress trickle simulation
            navProgressInterval = setInterval(() => {
                if (currentNavPercent < 78) {
                    const step = Math.max(1, (78 - currentNavPercent) * 0.18);
                    currentNavPercent += step;
                    bar.style.width = `${Math.min(78, currentNavPercent)}%`;
                }
            }, 180);
        },

        done() {
            const bar = getOrCreateNavProgressBar();
            if (!bar) return;

            clearInterval(navProgressInterval);
            currentNavPercent = 100;
            bar.style.transition = 'width 0.16s ease-out, opacity 0.24s ease-out 0.12s';
            bar.style.width = '100%';
            bar.style.opacity = '0';

            // Remove navigation state from document
            document.body.classList.remove('alameen-navigating-out');

            navProgressResetTimer = setTimeout(() => {
                bar.style.transition = 'none';
                bar.style.width = '0%';
                currentNavPercent = 0;
            }, 380);
        }
    };

    // =========================================================================
    // 2. TOP SCROLL PROGRESS BAR (Dynamic scroll percentage depth)
    // =========================================================================
    let scrollProgressBar = null;

    function getOrCreateScrollProgressBar() {
        if (scrollProgressBar && document.body.contains(scrollProgressBar)) {
            return scrollProgressBar;
        }

        scrollProgressBar = document.getElementById('alameen-scroll-progress');
        if (!scrollProgressBar) {
            scrollProgressBar = document.createElement('div');
            scrollProgressBar.id = 'alameen-scroll-progress';
            scrollProgressBar.setAttribute('aria-hidden', 'true');
            if (document.body) {
                document.body.prepend(scrollProgressBar);
            }
        }
        return scrollProgressBar;
    }

    function updateScrollProgress() {
        const bar = getOrCreateScrollProgressBar();
        if (!bar) return;

        const scrollTop = window.scrollY || document.documentElement.scrollTop || 0;
        const scrollHeight = document.documentElement.scrollHeight || document.body.scrollHeight || 0;
        const clientHeight = window.innerHeight || document.documentElement.clientHeight || 0;
        const maxScroll = scrollHeight - clientHeight;

        if (maxScroll > 10) {
            const pct = Math.min(100, Math.max(0, (scrollTop / maxScroll) * 100));
            bar.style.width = `${pct}%`;
        } else {
            bar.style.width = '0%';
        }
    }

    // =========================================================================
    // 3. STICKY HEADER ELEVATION & BLUR ON SCROLL
    // =========================================================================
    function updateHeaderElevation() {
        const isScrolled = (window.scrollY || document.documentElement.scrollTop || 0) > 15;
        const headers = document.querySelectorAll('header, [data-flux-header], .student-header, .admin-header, .sidebar-header, #landing-header');
        headers.forEach((header) => {
            if (isScrolled) {
                header.classList.add('is-scrolled');
            } else {
                header.classList.remove('is-scrolled');
            }
        });
    }

    // =========================================================================
    // 4. FLOATING SPRING "BACK TO TOP" BUTTON
    // =========================================================================
    let backToTopButton = null;

    function getOrCreateBackToTopButton() {
        if (backToTopButton && document.body.contains(backToTopButton)) {
            return backToTopButton;
        }

        backToTopButton = document.getElementById('alameen-back-to-top');
        if (!backToTopButton) {
            backToTopButton = document.createElement('button');
            backToTopButton.id = 'alameen-back-to-top';
            backToTopButton.setAttribute('type', 'button');
            backToTopButton.setAttribute('aria-label', 'العودة إلى أعلى الصفحة');
            backToTopButton.innerHTML = `
                <svg class="size-5 transition-transform duration-200 group-hover:-translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
                </svg>
            `;
            if (document.body) {
                document.body.appendChild(backToTopButton);
            }

            backToTopButton.addEventListener('click', () => {
                const mainEl = document.querySelector('.alameen-dashboard-layout .alameen-content-wrap > main');
                if (mainEl) {
                    mainEl.scrollTo({ top: 0, behavior: 'smooth' });
                }
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        }
        return backToTopButton;
    }

    function updateBackToTopVisibility() {
        const btn = getOrCreateBackToTopButton();
        if (!btn) return;

        const mainEl = document.querySelector('.alameen-dashboard-layout .alameen-content-wrap > main');
        const scrollTop = (mainEl && mainEl.scrollTop > 0) ? mainEl.scrollTop : (window.scrollY || document.documentElement.scrollTop || 0);
        if (scrollTop > 280) {
            btn.classList.add('show');
        } else {
            btn.classList.remove('show');
        }
    }

    // Consolidated passive scroll listener (throttled via requestAnimationFrame)
    let isScrollTicking = false;
    function handleScroll() {
        if (!isScrollTicking) {
            window.requestAnimationFrame(() => {
                updateScrollProgress();
                updateHeaderElevation();
                updateBackToTopVisibility();
                isScrollTicking = false;
            });
            isScrollTicking = true;
        }
    }

    // =========================================================================
    // 5. SCROLL REVEAL ANIMATIONS (IntersectionObserver Engine)
    // =========================================================================
    let scrollObserver = null;

    function initScrollReveals() {
        // Disconnect any existing observer
        if (scrollObserver) {
            try {
                scrollObserver.disconnect();
            } catch {}
        }

        const candidateSelectors = [
            '.reveal-on-scroll',
            '.reveal-left',
            '.reveal-right',
            '.reveal-zoom',
            '.reveal-fade',
            '.course-card',
            '.review-card',
            '.stat-card',
            '[data-flux-card]',
            'main section',
            '.grid > div:not([data-no-reveal])',
            '.grid > a:not([data-no-reveal])',
            'main > div > div:not([data-no-reveal])'
        ];

        const elementsToObserve = new Set();

        candidateSelectors.forEach((selector) => {
            document.querySelectorAll(selector).forEach((el) => {
                // Ignore navigation bars, modals, toast, headers, sidebars
                if (
                    !el.closest('header') &&
                    !el.closest('nav') &&
                    !el.closest('aside') &&
                    !el.closest('#sidebar-overlay') &&
                    !el.closest('#alameen-loader') &&
                    !el.closest('#alameen-nav-progress') &&
                    !el.closest('#alameen-scroll-progress') &&
                    !el.closest('#alameen-back-to-top') &&
                    !el.classList.contains('fixed') &&
                    !el.classList.contains('sticky') &&
                    !el.id.includes('modal') &&
                    el.id !== 'toast' &&
                    !el.hasAttribute('data-no-reveal')
                ) {
                    elementsToObserve.add(el);
                }
            });
        });

        if ('IntersectionObserver' in window) {
            const observerOptions = {
                root: null,
                rootMargin: '0px 0px -30px 0px',
                threshold: 0.05
            };

            scrollObserver = new IntersectionObserver((entries, obs) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('revealed');
                        obs.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            const windowHeight = window.innerHeight || document.documentElement.clientHeight || 800;

            elementsToObserve.forEach((el) => {
                // Ensure the base reveal animation class is present
                if (
                    !el.classList.contains('reveal-on-scroll') &&
                    !el.classList.contains('reveal-left') &&
                    !el.classList.contains('reveal-right') &&
                    !el.classList.contains('reveal-zoom') &&
                    !el.classList.contains('reveal-fade')
                ) {
                    el.classList.add('reveal-on-scroll');
                }

                // Stagger siblings inside grids/flex layouts
                const parent = el.parentElement;
                if (parent && (parent.classList.contains('grid') || parent.classList.contains('flex-wrap'))) {
                    const siblingIndex = Array.from(parent.children).indexOf(el);
                    if (siblingIndex > 0) {
                        const delay = Math.min(350, siblingIndex * 65);
                        el.style.transitionDelay = `${delay}ms`;
                    }
                }

                // Check if element is already within the initial viewport
                const rect = el.getBoundingClientRect();
                if (rect.top < windowHeight - 40 && rect.bottom > 0) {
                    // Stagger viewport reveal slightly for cascade effect
                    setTimeout(() => {
                        el.classList.add('revealed');
                    }, 60);
                } else {
                    scrollObserver.observe(el);
                }
            });
        } else {
            // Fallback for older browsers
            elementsToObserve.forEach((el) => el.classList.add('revealed'));
        }
    }

    // =========================================================================
    // 6. MICRO-INTERACTIONS (Card Hover Lift, Button Shine, Table Row Hover)
    // =========================================================================
    function initMicroInteractions() {
        // A. Card Lift on Hover
        const cardSelectors = [
            '.bg-white.rounded-2xl',
            '.bg-white.rounded-3xl',
            '.bg-white.rounded-xl.border',
            '.bg-white.border.rounded-2xl',
            '.course-card',
            '.review-card',
            '.stat-card',
            '[data-flux-card]',
            'div[class*="shadow-xs"][class*="rounded-2xl"]'
        ];

        document.querySelectorAll(cardSelectors.join(', ')).forEach((card) => {
            if (
                !card.closest('header') &&
                !card.closest('nav') &&
                !card.closest('aside') &&
                !card.id.includes('modal') &&
                !card.classList.contains('hover-lift') &&
                !card.hasAttribute('data-no-hover')
            ) {
                card.classList.add('hover-lift');
            }
        });

        // B. Button Shine Effect on Primary/Accent Buttons
        const btnSelectors = [
            'button.bg-\\[\\#012383\\]',
            'button.bg-\\[\\#0B2A8F\\]',
            'a.bg-\\[\\#012383\\]',
            'a.bg-\\[\\#0B2A8F\\]',
            '.btn-primary',
            'button[type="submit"]:not(.no-shine)'
        ];

        document.querySelectorAll(btnSelectors.join(', ')).forEach((btn) => {
            if (!btn.classList.contains('btn-shine')) {
                btn.classList.add('btn-shine');
            }
        });

        // C. Table Row Smooth Hover Highlight
        document.querySelectorAll('table tbody tr').forEach((tr) => {
            if (!tr.classList.contains('table-row-hover')) {
                tr.classList.add('table-row-hover');
            }
        });

        // D. Filter Pills & Interactive Badges
        document.querySelectorAll('[data-filter], [data-tab], [data-cat]').forEach((pill) => {
            if (!pill.classList.contains('badge-interactive')) {
                pill.classList.add('badge-interactive');
            }
        });
    }

    // =========================================================================
    // 7. PAGE ENTRANCE & SIDEBAR TABS STAGGER (On Refresh & On Navigate)
    // =========================================================================
    function triggerSidebarStagger() {
        const sidebarTabs = document.querySelectorAll('nav a.sidebar-nav-link');
        if (!sidebarTabs.length) return;

        if (window.innerWidth < 1024) {
            sidebarTabs.forEach((tab) => {
                tab.classList.remove('sidebar-tab-animate');
                tab.style.animationDelay = '';
            });
            return;
        }

        sidebarTabs.forEach((tab, index) => {
            tab.classList.remove('sidebar-tab-animate');
            void tab.offsetWidth; // Force reflow
            tab.style.animationDelay = `${(index + 1) * 35}ms`;
            tab.classList.add('sidebar-tab-animate');
        });
    }

    function triggerPageEntrance() {
        const contentContainers = document.querySelectorAll(
            '.alameen-content-wrap, main, #landing-frame, [data-flux-container]'
        );

        contentContainers.forEach((container) => {
            container.classList.remove('alameen-page-transition-enter');
            // Trigger reflow to restart animation cleanly
            void container.offsetWidth;
            container.classList.add('alameen-page-transition-enter');

            // Cleanup class after animation completes
            setTimeout(() => {
                container.classList.remove('alameen-page-transition-enter');
            }, 600);
        });

        // Trigger 1-by-1 cascade for sidebar tabs
        triggerSidebarStagger();
    }

    // =========================================================================
    // 8. MASTER INITIALIZATION & EVENT COORDINATION
    // =========================================================================
    function initAll() {
        getOrCreateNavProgressBar();
        getOrCreateScrollProgressBar();
        getOrCreateBackToTopButton();

        updateScrollProgress();
        updateHeaderElevation();
        updateBackToTopVisibility();

        initScrollReveals();
        initMicroInteractions();
    }

    // A. Initial Page Load / Hard Refresh
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            initAll();
        }, { once: true });
    } else {
        initAll();
    }

    window.addEventListener('load', () => {
        updateScrollProgress();
        updateHeaderElevation();
        updateBackToTopVisibility();
        // Give time for any lazy-loaded content to calculate bounds
        setTimeout(initScrollReveals, 150);
    }, { once: true });

    // B. Bind Scroll Events
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('resize', handleScroll, { passive: true });
    document.addEventListener('scroll', handleScroll, { passive: true, capture: true });

    // C. Livewire 3/4 Lifecycle Events (wire:navigate)
    document.addEventListener('livewire:navigating', () => {
        navProgress.start();
    });

    document.addEventListener('livewire:navigated', () => {
        navProgress.done();

        // Scroll to top on navigation if no anchor hash
        if (!window.location.hash) {
            try {
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
            } catch {
                window.scrollTo(0, 0);
            }
        }

        // Reset scroll progress bar
        if (scrollProgressBar) {
            scrollProgressBar.style.width = '0%';
        }

        // Hide back to top button
        if (backToTopButton) {
            backToTopButton.classList.remove('show');
        }

        // Trigger entrance animation for newly navigated page
        triggerPageEntrance();

        // Re-initialize reveals and micro-interactions for newly mounted DOM
        setTimeout(() => {
            initAll();
        }, 50);
    });

    // D. Standard Internal Link Clicks (Instant Progress Feedback for non-wire:navigate links)
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a');
        if (!link || !link.href) return;

        // Skip non-navigational clicks
        if (
            link.target === '_blank' ||
            link.hasAttribute('download') ||
            link.getAttribute('href').startsWith('#') ||
            link.getAttribute('href').startsWith('javascript:') ||
            link.getAttribute('href').startsWith('mailto:') ||
            link.getAttribute('href').startsWith('tel:') ||
            link.hasAttribute('data-no-animate') ||
            event.ctrlKey ||
            event.metaKey ||
            event.shiftKey ||
            event.altKey
        ) {
            return;
        }

        // Verify same-origin link
        try {
            const linkUrl = new URL(link.href, window.location.origin);
            if (linkUrl.origin === window.location.origin && linkUrl.pathname !== window.location.pathname) {
                // If not using wire:navigate, start the progress bar for immediate feedback
                if (!link.hasAttribute('wire:navigate')) {
                    navProgress.start();
                }
            }
        } catch {}
    }, { passive: true });

    // Global exposure
    if (typeof window !== 'undefined') {
        window.AlameenAnimations = {
            navProgress,
            initScrollReveals,
            initMicroInteractions,
            triggerPageEntrance,
            triggerSidebarStagger,
            refresh: initAll
        };
    }
})();
