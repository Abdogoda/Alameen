/**
 * AL-AMEEN EDUCATIONAL PLATFORM - ANIMATIONS & THEME CONTROLLER
 * Speech Therapy & Phonetics Theme (النطق والتخاطب)
 * Controls: Themed Loader, Scroll Progress, Scroll Reveal, Micro-interactions & Hover Effects
 */

(function () {
  'use strict';

  // 1. Resolve relative asset root for logo
  function getLogoPath() {
    const path = window.location.pathname.toLowerCase();
    if (path.includes('/dashboard/') || path.includes('/pages/')) {
      return '../assets/images/logo.png';
    }
    return '/assets/images/logo.png';
  }

  // 2. Initialize Themed Loader
  function initThemedLoader() {
    let loader = document.getElementById('alameen-loader');
    if (!loader) {
      loader = document.createElement('div');
      loader.id = 'alameen-loader';
      if (document.documentElement.classList.contains('dark')) {
        loader.classList.add('dark');
      }
      loader.setAttribute('role', 'status');
      loader.setAttribute('aria-label', 'جاري تحميل منصة الأمين');

      const logoSrc = getLogoPath();

      loader.innerHTML = `
        <div class="alameen-loader-resonator">
          <div class="alameen-voice-wave alameen-voice-wave-1"></div>
          <div class="alameen-voice-wave alameen-voice-wave-2"></div>
          <div class="alameen-voice-wave alameen-voice-wave-3"></div>
          <div class="alameen-loader-logo-capsule">
            <img src="${logoSrc}" alt="منصة الأمين" onerror="this.src='/assets/images/logo.png'; this.onerror=null;" />
          </div>
        </div>

        <div class="alameen-loader-equalizer">
          <div class="alameen-eq-bar"></div>
          <div class="alameen-eq-bar"></div>
          <div class="alameen-eq-bar"></div>
          <div class="alameen-eq-bar"></div>
          <div class="alameen-eq-bar"></div>
        </div>

        <h2 class="alameen-loader-title">منصة الأمين</h2>
        <p class="alameen-loader-subtitle">المنصة التعليمية المتخصصة في التخاطب وعلاج النطق</p>

        <div class="alameen-loader-track">
          <div class="alameen-loader-indicator"></div>
        </div>
      `;

      if (document.body) {
        document.body.prepend(loader);
      } else {
        document.addEventListener('DOMContentLoaded', () => document.body.prepend(loader));
      }
    }

    // Dismiss Loader with smooth ease-out
    const startTime = performance.now();
    const dismissLoader = () => {
      const elapsed = performance.now() - startTime;
      const minDisplayTime = 380; // Provide pleasant brand impression
      const remaining = Math.max(0, minDisplayTime - elapsed);

      setTimeout(() => {
        if (loader) {
          loader.classList.add('loader-hidden');
          setTimeout(() => {
            loader.style.display = 'none';
          }, 480);
        }
      }, remaining);
    };

    if (document.readyState === 'complete') {
      dismissLoader();
    } else {
      window.addEventListener('load', dismissLoader);
      // Fallback safeguard so loader never blocks navigation
      setTimeout(dismissLoader, 1400);
    }
  }

  // 3. Initialize Top Scroll Progress Bar
  function initScrollProgress() {
    let progressBar = document.getElementById('alameen-scroll-progress');
    if (!progressBar) {
      progressBar = document.createElement('div');
      progressBar.id = 'alameen-scroll-progress';
      if (document.body) {
        document.body.prepend(progressBar);
      }
    }

    let ticking = false;
    const updateProgress = () => {
      const scrollTop = window.scrollY || document.documentElement.scrollTop;
      const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      if (docHeight > 0) {
        const percent = Math.min(100, Math.max(0, (scrollTop / docHeight) * 100));
        progressBar.style.width = percent + '%';
      }
      ticking = false;
    };

    window.addEventListener(
      'scroll',
      () => {
        if (!ticking) {
          window.requestAnimationFrame(updateProgress);
          ticking = true;
        }
      },
      { passive: true }
    );
  }

  // 4. Initialize IntersectionObserver for Scroll Reveals
  function initScrollReveals() {
    // Select elements to reveal
    const autoSelectors = [
      'section',
      '#landing-frame > div',
      '.course-card',
      '.review-card',
      'aside + main > div',
      'main > div > div',
      '.stat-card',
      '.grid > div',
      '.reveal-on-scroll'
    ];

    const elementsToReveal = new Set();
    autoSelectors.forEach((selector) => {
      document.querySelectorAll(selector).forEach((el) => {
        // Skip header, nav, modals, loaders
        if (
          !el.closest('header') &&
          !el.closest('nav') &&
          !el.closest('#sidebar-overlay') &&
          !el.closest('#alameen-loader') &&
          !el.classList.contains('fixed') &&
          !el.classList.contains('sticky') &&
          el.id !== 'toast' &&
          !el.id.includes('modal')
        ) {
          elementsToReveal.add(el);
        }
      });
    });

    if ('IntersectionObserver' in window) {
      const observerOptions = {
        root: null,
        rootMargin: '0px 0px -40px 0px',
        threshold: 0.08
      };

      const revealObserver = new IntersectionObserver((entries, obs) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add('revealed');
            obs.unobserve(entry.target);
          }
        });
      }, observerOptions);

      elementsToReveal.forEach((el, index) => {
        if (!el.classList.contains('reveal-on-scroll') && !el.classList.contains('reveal-left') && !el.classList.contains('reveal-right')) {
          el.classList.add('reveal-on-scroll');
        }

        // Stagger grid siblings
        const parent = el.parentElement;
        if (parent && (parent.classList.contains('grid') || parent.classList.contains('flex-wrap'))) {
          const siblingIndex = Array.from(parent.children).indexOf(el);
          if (siblingIndex > 0) {
            const delay = Math.min(350, siblingIndex * 80);
            el.style.transitionDelay = `${delay}ms`;
          }
        }

        revealObserver.observe(el);
      });
    } else {
      // Fallback for older browsers
      elementsToReveal.forEach((el) => el.classList.add('revealed'));
    }
  }

  // 5. Enhance Hover and Click Micro-Interactions
  function initHoverMicroInteractions() {
    // A. Card Lift on Hover
    const cardSelectors = [
      '.bg-white.rounded-2xl',
      '.bg-white.rounded-3xl',
      '.bg-white.rounded-xl.border',
      '.bg-white.border.rounded-2xl',
      '.course-card',
      '.review-card',
      'div[class*="shadow-xs"][class*="rounded-2xl"]'
    ];

    document.querySelectorAll(cardSelectors.join(', ')).forEach((card) => {
      if (
        !card.closest('header') &&
        !card.closest('nav') &&
        !card.id.includes('modal') &&
        !card.classList.contains('hover-lift')
      ) {
        card.classList.add('hover-lift');
      }
    });

    // B. Button Shine Effect
    const btnSelectors = [
      'button.bg-\\[\\#012383\\]',
      'button.bg-\\[\\#0B2A8F\\]',
      'a.bg-\\[\\#012383\\]',
      'a.bg-\\[\\#0B2A8F\\]',
      '.btn-primary',
      'button[type="submit"]'
    ];

    document.querySelectorAll(btnSelectors.join(', ')).forEach((btn) => {
      btn.classList.add('btn-shine');
    });

    // C. Table Rows Smooth Hover
    document.querySelectorAll('table tbody tr').forEach((tr) => {
      tr.classList.add('table-row-hover');
    });

    // D. Badges & Filter Pills
    document.querySelectorAll('[data-filter], [data-tab], [data-cat]').forEach((pill) => {
      pill.classList.add('badge-interactive');
    });
  }

  // 6. Floating Back to Top Button
  function initBackToTop() {
    let btn = document.getElementById('alameen-back-to-top');
    if (!btn) {
      btn = document.createElement('button');
      btn.id = 'alameen-back-to-top';
      btn.setAttribute('type', 'button');
      btn.setAttribute('aria-label', 'العودة إلى أعلى الصفحة');
      btn.innerHTML = `
        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
        </svg>
      `;
      if (document.body) {
        document.body.appendChild(btn);
      }
    }

    window.addEventListener(
      'scroll',
      () => {
        if (window.scrollY > 320) {
          btn.classList.add('show');
        } else {
          btn.classList.remove('show');
        }
      },
      { passive: true }
    );

    btn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });
  }

  // 7. Master Initialization
  function initAll() {
    initThemedLoader();
    initScrollProgress();
    initScrollReveals();
    initHoverMicroInteractions();
    initBackToTop();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
