/**
 * Al-Ameen Unified One-Page Landing Master Script
 * Coordinates:
 * 1. ScrollSpy: Highlighting the active header nav link based on scroll position
 * 2. Smooth Navigation: Header links glide to sections with sticky header offset
 * 3. Hero Slider: Auto-advancing slides with progress timer, dots, and journey tabs sync
 * 4. Course Catalog Filter & Search: Live filtering in the #courses section
 * 5. Contact Inquiry Workflow: Topic pills & form submission toast
 * 6. Review Modal & Feedback Workflow
 */

document.addEventListener('DOMContentLoaded', () => {
  // ==========================================
  // 1. SCROLLSPY & HEADER LINK SYNC
  // ==========================================
  const sections = ['home', 'courses', 'about', 'reviews', 'contact']
    .map(id => document.getElementById(id))
    .filter(Boolean);

  const navLinks = Array.from(document.querySelectorAll('nav a[href^="#"], #mobile-nav-menu a[href^="#"]'));

  function updateActiveNavLink() {
    const scrollPos = window.scrollY + 140; // Offset for sticky header

    let currentSectionId = sections[0]?.id || 'home';

    sections.forEach(section => {
      const top = section.offsetTop;
      const height = section.offsetHeight;
      if (scrollPos >= top && scrollPos < top + height) {
        currentSectionId = section.id;
      }
    });

    // Check if user scrolled to bottom of page
    if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 80) {
      currentSectionId = sections[sections.length - 1]?.id || currentSectionId;
    }

    navLinks.forEach(link => {
      const targetHash = link.getAttribute('href');
      const isMatch = targetHash === `#${currentSectionId}`;

      if (link.closest('#mobile-nav-menu')) {
        // Mobile Link
        if (isMatch) {
          link.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
          link.classList.remove('text-[#012383]');
        } else {
          link.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');
          link.classList.add('text-[#012383]');
        }
      } else {
        // Desktop Link
        if (isMatch) {
          link.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
          link.classList.remove('text-[#012383]', 'hover:bg-[#EAF1FB]/50');
        } else {
          link.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');
          link.classList.add('text-[#012383]', 'hover:bg-[#EAF1FB]/50');
        }
      }
    });
  }

  window.addEventListener('scroll', updateActiveNavLink, { passive: true });
  updateActiveNavLink();

  // Note: Mobile Navigation Menu Toggle is handled reactively by Alpine.js in marketing-header.blade.php

  // Smooth scroll offset for anchor clicks & hash synchronization
  navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
      const hash = link.getAttribute('href');
      if (hash && hash.startsWith('#')) {
        const target = document.querySelector(hash);
        if (target) {
          e.preventDefault();
          const headerOffset = 88;
          const elementPosition = target.getBoundingClientRect().top;
          const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

          window.scrollTo({
            top: offsetPosition,
            behavior: 'smooth'
          });

          try {
            history.replaceState(null, '', hash);
          } catch (err) {}
        }
      }
    });
  });

  // Handle URL hash on initial page load (e.g. navigating from /pages/courses.html back to /#about)
  if (window.location.hash) {
    const initialTarget = document.querySelector(window.location.hash);
    if (initialTarget) {
      setTimeout(() => {
        const headerOffset = 88;
        const elementPosition = initialTarget.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
        window.scrollTo({
          top: offsetPosition,
          behavior: 'smooth'
        });
      }, 150);
    }
  }

  // ==========================================
  // 2. HERO SLIDER ENGINE (Section: #home)
  // ==========================================
  const sliderContainer = document.getElementById('hero-slider');
  if (sliderContainer) {
    const slides = Array.from(sliderContainer.querySelectorAll('.hero-slide'));
    const dots = Array.from(document.querySelectorAll('.slider-dot'));
    const stepTabs = Array.from(document.querySelectorAll('[data-step-target]'));
    const prevBtn = document.getElementById('slider-prev');
    const nextBtn = document.getElementById('slider-next');
    
    let currentIndex = 0;
    const totalSlides = slides.length;
    let autoPlayTimer = null;
    const autoPlayDelay = 6000;

    function goToSlide(index) {
      currentIndex = (index + totalSlides) % totalSlides;

      // Update Slides with is-active class (grid stacking handles zero-jump transition)
      slides.forEach((slide, i) => {
        const isActive = i === currentIndex;
        if (isActive) {
          slide.classList.add('is-active');
        } else {
          slide.classList.remove('is-active');
        }
      });

      // Update Horizontal Dots with smooth morphing
      dots.forEach((dot, i) => {
        if (i === currentIndex) {
          dot.classList.remove('w-2.5', 'bg-[#CBD5E1]', 'hover:bg-[#94A3B8]');
          dot.classList.add('w-9', 'bg-[#012383]', 'shadow-xs');
        } else {
          dot.classList.remove('w-9', 'bg-[#012383]', 'shadow-xs');
          dot.classList.add('w-2.5', 'bg-[#CBD5E1]', 'hover:bg-[#94A3B8]');
        }
      });

      // Synchronize Journey Tabs
      stepTabs.forEach(tab => {
        const target = parseInt(tab.getAttribute('data-step-target'), 10);
        const isActive = target === currentIndex;
        const dotEl = tab.querySelector('.step-dot');
        const textEl = tab.querySelector('.step-text');

        if (isActive) {
          tab.classList.add('bg-[#EAF1FB]', 'border-[#DEE8F7]', 'shadow-xs');
          tab.classList.remove('hover:bg-slate-100/60');
          if (dotEl) {
            dotEl.classList.remove('bg-slate-400');
            dotEl.classList.add('bg-[#0B2A8F]', 'scale-125');
          }
          if (textEl) {
            textEl.classList.remove('text-[#475569]', 'font-semibold');
            textEl.classList.add('text-[#0B2A8F]', 'font-black');
          }
        } else {
          tab.classList.remove('bg-[#EAF1FB]', 'border-[#DEE8F7]', 'shadow-xs');
          tab.classList.add('hover:bg-slate-100/60');
          if (dotEl) {
            dotEl.classList.remove('bg-[#0B2A8F]', 'scale-125');
            dotEl.classList.add('bg-slate-400');
          }
          if (textEl) {
            textEl.classList.remove('text-[#0B2A8F]', 'font-black');
            textEl.classList.add('text-[#475569]', 'font-semibold');
          }
        }
      });
    }

    function nextSlide() {
      goToSlide(currentIndex + 1);
    }

    function prevSlide() {
      goToSlide(currentIndex - 1);
    }

    function startAutoPlay() {
      stopAutoPlay();
      autoPlayTimer = setInterval(nextSlide, autoPlayDelay);
    }

    function stopAutoPlay() {
      if (autoPlayTimer) {
        clearInterval(autoPlayTimer);
        autoPlayTimer = null;
      }
    }

    if (nextBtn) {
      nextBtn.addEventListener('click', () => {
        nextSlide();
        startAutoPlay();
      });
    }

    if (prevBtn) {
      prevBtn.addEventListener('click', () => {
        prevSlide();
        startAutoPlay();
      });
    }

    dots.forEach((dot, idx) => {
      dot.addEventListener('click', () => {
        goToSlide(idx);
        startAutoPlay();
      });
    });

    stepTabs.forEach(tab => {
      tab.addEventListener('click', () => {
        const target = parseInt(tab.getAttribute('data-step-target'), 10);
        if (!isNaN(target)) {
          goToSlide(target);
          startAutoPlay();
        }
      });
    });

    sliderContainer.addEventListener('mouseenter', stopAutoPlay);
    sliderContainer.addEventListener('mouseleave', startAutoPlay);

    // Initial setup
    goToSlide(0);
    startAutoPlay();
  }

  // ==========================================
  // 3. COURSES CATALOG FILTER & SEARCH (#courses)
  // ==========================================
  const catButtons = document.querySelectorAll('.catalog-filter');
  const courseCards = document.querySelectorAll('.course-card');
  const searchInput = document.getElementById('course-search-input');
  const emptyState = document.getElementById('catalog-empty-state');

  let activeCat = 'all';
  let searchQuery = '';

  function filterCatalog() {
    let visibleCount = 0;

    courseCards.forEach(card => {
      const cardCat = card.getAttribute('data-category') || '';
      const cardTitle = (card.getAttribute('data-title') || '').toLowerCase();
      const cardText = card.textContent.toLowerCase();

      const matchCat = activeCat === 'all' || cardCat.includes(activeCat);
      const matchSearch = !searchQuery || cardTitle.includes(searchQuery) || cardText.includes(searchQuery);

      if (matchCat && matchSearch) {
        card.style.display = 'flex';
        visibleCount++;
      } else {
        card.style.display = 'none';
      }
    });

    if (emptyState) {
      if (visibleCount === 0) {
        emptyState.classList.remove('hidden');
        emptyState.classList.add('flex');
      } else {
        emptyState.classList.add('hidden');
        emptyState.classList.remove('flex');
      }
    }
  }

  catButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      catButtons.forEach(b => {
        b.classList.remove('active', 'bg-[#012383]', 'text-white');
        b.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
      });
      btn.classList.add('active', 'bg-[#012383]', 'text-white');
      btn.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');

      activeCat = btn.getAttribute('data-cat') || 'all';
      filterCatalog();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      searchQuery = e.target.value.trim().toLowerCase();
      filterCatalog();
    });
  }

  // ==========================================
  // 4. CONTACT WORKFLOW & TOPIC PILLS (#contact)
  // ==========================================
  const topicPills = document.querySelectorAll('.topic-pill');
  topicPills.forEach(pill => {
    pill.addEventListener('click', () => {
      topicPills.forEach(p => {
        p.classList.remove('active', 'bg-[#012383]', 'text-white');
        p.classList.add('bg-[#EAF1FB]', 'text-[#0B2A8F]');
      });
      pill.classList.add('active', 'bg-[#012383]', 'text-white');
      pill.classList.remove('bg-[#EAF1FB]', 'text-[#0B2A8F]');
    });
  });

  const contactForm = document.getElementById('contact-form');
  const toast = document.getElementById('toast');
  const toastMessage = document.getElementById('toast-message');

  if (contactForm) {
    contactForm.addEventListener('submit', (e) => {
      e.preventDefault();
      const isEn = document.documentElement.lang === 'en';
      if (toast && toastMessage) {
        toastMessage.textContent = isEn 
          ? 'Your inquiry has been received! Our support team will reply shortly.' 
          : 'تم استلام استفسارك بنجاح! سيتواصل معك فريق الدعم قريباً.';
        toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
        setTimeout(() => {
          toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
        }, 3500);
      }
      contactForm.reset();
    });
  }

  // ==========================================
  // 5. REVIEWS SUBMISSION MODAL (#reviews)
  // ==========================================
  const openReviewModalBtn = document.getElementById('open-review-modal-btn');
  const reviewModal = document.getElementById('review-modal');
  const closeReviewModalBtn = document.getElementById('close-review-modal-btn');
  const cancelReviewModalBtn = document.getElementById('cancel-review-modal-btn');
  const submitReviewBtn = document.getElementById('submit-review-modal-btn');
  const modalStars = document.querySelectorAll('.modal-star');
  let selectedRating = 5;

  function showReviewModal() {
    if (reviewModal) {
      reviewModal.classList.remove('hidden');
      reviewModal.classList.add('flex');
    }
  }

  function hideReviewModal() {
    if (reviewModal) {
      reviewModal.classList.add('hidden');
      reviewModal.classList.remove('flex');
    }
  }

  if (openReviewModalBtn) openReviewModalBtn.addEventListener('click', showReviewModal);
  if (closeReviewModalBtn) closeReviewModalBtn.addEventListener('click', hideReviewModal);
  if (cancelReviewModalBtn) cancelReviewModalBtn.addEventListener('click', hideReviewModal);

  modalStars.forEach(star => {
    star.addEventListener('click', () => {
      selectedRating = parseInt(star.getAttribute('data-value'), 10) || 5;
      modalStars.forEach(s => {
        const val = parseInt(s.getAttribute('data-value'), 10);
        if (val <= selectedRating) {
          s.classList.add('text-amber-400');
          s.classList.remove('text-slate-300');
        } else {
          s.classList.remove('text-amber-400');
          s.classList.add('text-slate-300');
        }
      });
    });
  });

  if (submitReviewBtn) {
    submitReviewBtn.addEventListener('click', () => {
      const title = document.getElementById('modal-review-title')?.value.trim();
      const text = document.getElementById('modal-review-text')?.value.trim();

      if (!title || !text) {
        alert(document.documentElement.lang === 'en' ? 'Please fill in all fields.' : 'يرجى كتابة عنوان التقييم وتفاصيل التجربة.');
        return;
      }

      hideReviewModal();
      if (document.getElementById('modal-review-title')) document.getElementById('modal-review-title').value = '';
      if (document.getElementById('modal-review-text')) document.getElementById('modal-review-text').value = '';

      if (toast && toastMessage) {
        const isEn = document.documentElement.lang === 'en';
        toastMessage.textContent = isEn ? 'Your review has been submitted successfully!' : 'تم نشر تقييمك بنجاح! شكراً لمشاركتك.';
        toast.classList.remove('translate-y-20', 'opacity-0', 'pointer-events-none');
        setTimeout(() => {
          toast.classList.add('translate-y-20', 'opacity-0', 'pointer-events-none');
        }, 3500);
      }
    });
  }

  // ==========================================
  // 6. CALM SCROLL REVEAL MICRO-ANIMATIONS
  // ==========================================
  const revealElements = document.querySelectorAll('.reveal-on-scroll');
  if ('IntersectionObserver' in window && revealElements.length > 0) {
    const revealObserver = new IntersectionObserver((entries, observer) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-revealed');
          observer.unobserve(entry.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -40px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));
  } else {
    revealElements.forEach(el => el.classList.add('is-revealed'));
  }
});
