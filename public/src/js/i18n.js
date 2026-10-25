class I18nEngine {
  constructor() {
    const htmlLang = typeof document !== 'undefined' ? (document.documentElement.getAttribute('lang') || '').substring(0, 2) : null;
    const urlLang = typeof window !== 'undefined' ? new URLSearchParams(window.location.search).get('lang') : null;
    this.currentLang = htmlLang || urlLang || localStorage.getItem('app_lang') || 'ar';
    this.translations = {};
    this.isDropdownOpen = false;
  }

  async init() {
    await this.loadTranslations(this.currentLang);
    this.applyLanguage(this.currentLang);
    this.setupDropdown();
    this.setupMobileMenu();
    this.bindEvents();
  }

  async loadTranslations(lang) {
    if (!this.translations[lang]) {
      try {
        const baseUrl = (typeof import.meta !== 'undefined' && import.meta.env && import.meta.env.BASE_URL) || '/';
        const normalizedBase = baseUrl.endsWith('/') ? baseUrl : `${baseUrl}/`;
        const response = await fetch(`${normalizedBase}locales/${lang}.json`);
        this.translations[lang] = await response.json();
      } catch (err) {
        console.error(`Failed to load ${lang} locale:`, err);
      }
    }
  }

  applyLanguage(lang) {
    this.currentLang = lang;
    localStorage.setItem('app_lang', lang);

    const dir = lang === 'ar' ? 'rtl' : 'ltr';
    document.documentElement.setAttribute('lang', lang);
    document.documentElement.setAttribute('dir', dir);

    const dict = this.translations[lang] || {};

    // 1. Text elements
    document.querySelectorAll('[data-i18n]').forEach((el) => {
      const key = el.getAttribute('data-i18n');
      const val = this.getNestedValue(dict, key);
      if (val !== undefined) {
        if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
          el.value = val;
        }
        el.textContent = val;
      }
    });

    // 2. Input placeholders
    document.querySelectorAll('[data-i18n-placeholder]').forEach((el) => {
      const key = el.getAttribute('data-i18n-placeholder');
      const val = this.getNestedValue(dict, key);
      if (val !== undefined) {
        el.setAttribute('placeholder', val);
      }
    });

    // 3. Aria attributes
    document.querySelectorAll('[data-i18n-aria]').forEach((el) => {
      const key = el.getAttribute('data-i18n-aria');
      const val = this.getNestedValue(dict, key);
      if (val !== undefined) {
        el.setAttribute('aria-label', val);
      }
    });

    // 4. Update language toggle labels
    const labelEl = document.getElementById('current-lang-label');
    if (labelEl) {
      labelEl.textContent = lang === 'ar' ? 'العربية' : 'English';
    }

    // 5. Update active classes and checkmarks in dropdown
    document.querySelectorAll('[data-set-lang]').forEach((btn) => {
      const btnLang = btn.getAttribute('data-set-lang');
      const checkIcon = btn.querySelector('.lang-check-icon');
      if (btnLang === lang) {
        btn.classList.add('bg-[#EAF1FB]', 'font-bold', 'text-[#012383]');
        btn.classList.remove('text-[#475569]');
        if (checkIcon) checkIcon.classList.remove('opacity-0');
      } else {
        btn.classList.remove('bg-[#EAF1FB]', 'font-bold', 'text-[#012383]');
        btn.classList.add('text-[#475569]');
        if (checkIcon) checkIcon.classList.add('opacity-0');
      }
    });

    // Dispatch event
    window.dispatchEvent(new CustomEvent('languageChanged', { detail: { lang, dir } }));
  }

  getNestedValue(obj, path) {
    return path.split('.').reduce((prev, curr) => (prev ? prev[curr] : undefined), obj);
  }

  setupDropdown() {
    const toggleBtn = document.getElementById('lang-toggle-btn');
    const dropdown = document.getElementById('lang-dropdown');

    if (!toggleBtn || !dropdown) return;

    toggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      this.isDropdownOpen = !this.isDropdownOpen;
      dropdown.classList.toggle('hidden', !this.isDropdownOpen);
      dropdown.classList.toggle('flex', this.isDropdownOpen);
      toggleBtn.setAttribute('aria-expanded', String(this.isDropdownOpen));
      const caret = toggleBtn.querySelector('.lang-caret');
      if (caret) caret.style.transform = this.isDropdownOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    });

    document.addEventListener('click', (e) => {
      if (!toggleBtn.contains(e.target) && !dropdown.contains(e.target)) {
        this.isDropdownOpen = false;
        dropdown.classList.add('hidden');
        dropdown.classList.remove('flex');
        toggleBtn.setAttribute('aria-expanded', 'false');
        const caret = toggleBtn.querySelector('.lang-caret');
        if (caret) caret.style.transform = 'rotate(0deg)';
      }
    });
  }

  setupMobileMenu() {
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const navMenu = document.getElementById('mobile-nav-menu');
    if (!toggleBtn || !navMenu) return;

    toggleBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      const isHidden = navMenu.classList.contains('hidden');
      navMenu.classList.toggle('hidden', !isHidden);
      navMenu.classList.toggle('flex', isHidden);
    });

    document.addEventListener('click', (e) => {
      if (!toggleBtn.contains(e.target) && !navMenu.contains(e.target)) {
        navMenu.classList.add('hidden');
        navMenu.classList.remove('flex');
      }
    });
  }

  bindEvents() {
    document.querySelectorAll('[data-set-lang]').forEach((btn) => {
      btn.addEventListener('click', (e) => {
        const targetLang = e.currentTarget.getAttribute('data-set-lang');
        if (targetLang) {
          window.location.href = `/locale/${targetLang}`;
        }
      });
    });
  }
}

// Global initialization
window.addEventListener('DOMContentLoaded', () => {
  window.i18n = new I18nEngine();
  window.i18n.init();
});
