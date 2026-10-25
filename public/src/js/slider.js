/**
 * Al-Ameen Interactive Landing Hero Slider & Vertical Scroll Engine
 * Features:
 * - Fluid vertical & directional slide transitions with custom cubic-bezier easing
 * - Mouse Wheel / Trackpad vertical scroll detection with smart throttle
 * - Bi-directional touch swipe (both vertical gestures and horizontal RTL/LTR swipe)
 * - Auto-advancing with visual animated progress bar
 * - Micro-animation coordination & child element stagger
 * - 1:1 synchronization with 'رحلتك مع الأمين' bottom journey tabs
 * - Floating Prev / Next chevrons & interactive vertical scroll cue
 * - Keyboard navigation (ArrowUp, ArrowDown, ArrowLeft, ArrowRight)
 */

class HeroSlider {
  constructor() {
    this.container = document.getElementById('hero-slider');
    if (!this.container) return;

    this.slides = Array.from(this.container.querySelectorAll('.hero-slide'));
    this.dots = Array.from(document.querySelectorAll('.slider-dot'));
    this.verticalDots = Array.from(document.querySelectorAll('.vertical-slider-dot'));
    this.stepTabs = Array.from(document.querySelectorAll('[data-step-target]'));
    this.prevBtn = document.getElementById('slider-prev');
    this.nextBtn = document.getElementById('slider-next');
    this.scrollCue = document.getElementById('scroll-down-cue');
    this.progressBar = document.getElementById('slider-progress-bar');
    
    this.currentIndex = 0;
    this.totalSlides = this.slides.length;
    this.autoPlayTimer = null;
    this.progressAnimation = null;
    this.autoPlayDelay = 5500; // 5.5 seconds per slide
    this.isTransitioning = false;
    this.wheelCooldown = 700; // ms cooldown between mouse wheel scrolls
    this.lastWheelTime = 0;

    // Touch gesture tracking
    this.touchStartX = 0;
    this.touchStartY = 0;
    this.touchEndX = 0;
    this.touchEndY = 0;

    this.init();
  }

  init() {
    if (this.totalSlides === 0) return;

    this.goToSlide(0, 'init');
    this.bindEvents();
    this.startAutoPlay();
  }

  goToSlide(targetIndex, direction = 'forward') {
    if (this.isTransitioning && direction !== 'init') return;
    this.isTransitioning = true;

    const previousIndex = this.currentIndex;
    this.currentIndex = (targetIndex + this.totalSlides) % this.totalSlides;

    // Determine direction if not explicitly given
    if (direction !== 'init' && direction !== 'forward' && direction !== 'backward') {
      direction = this.currentIndex >= previousIndex ? 'forward' : 'backward';
    }

    // 1. Update Slides with Directional Smooth Vertical/Scale Transitions
    this.slides.forEach((slide, i) => {
      const isActive = i === this.currentIndex;
      const staggerEls = slide.querySelectorAll('.stagger-item');

      if (isActive) {
        // Prepare active slide
        slide.classList.remove(
          'opacity-0',
          'pointer-events-none',
          'scale-[0.96]',
          '-translate-y-10',
          'translate-y-10',
          'absolute',
          'inset-0'
        );
        slide.classList.add('opacity-100', 'pointer-events-auto', 'scale-100', 'translate-y-0', 'relative', 'z-10');

        // Trigger staggered reveal for text, badges, and CTAs
        staggerEls.forEach((el, idx) => {
          el.style.opacity = '0';
          el.style.transform = 'translateY(18px)';
          setTimeout(() => {
            el.style.transition = 'opacity 0.65s cubic-bezier(0.16, 1, 0.3, 1), transform 0.65s cubic-bezier(0.16, 1, 0.3, 1)';
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
          }, 80 + idx * 75);
        });

      } else {
        // Deactivate other slides
        slide.classList.remove('opacity-100', 'pointer-events-auto', 'scale-100', 'translate-y-0', 'relative', 'z-10');
        slide.classList.add('opacity-0', 'pointer-events-none', 'scale-[0.96]', 'absolute', 'inset-0');

        if (direction === 'forward') {
          slide.classList.add('-translate-y-10');
          slide.classList.remove('translate-y-10');
        } else {
          slide.classList.add('translate-y-10');
          slide.classList.remove('-translate-y-10');
        }
      }
    });

    // 2. Update Horizontal Dots
    this.dots.forEach((dot, i) => {
      const isActive = i === this.currentIndex;
      if (isActive) {
        dot.classList.remove('w-2.5', 'bg-[#CBD5E1]', 'hover:bg-[#94A3B8]');
        dot.classList.add('w-8', 'bg-[#012383]');
        dot.setAttribute('aria-current', 'true');
      } else {
        dot.classList.remove('w-8', 'bg-[#012383]');
        dot.classList.add('w-2.5', 'bg-[#CBD5E1]', 'hover:bg-[#94A3B8]');
        dot.removeAttribute('aria-current');
      }
    });

    // 3. Update Vertical Indicator Dots (if present)
    this.verticalDots.forEach((vDot, i) => {
      const isActive = i === this.currentIndex;
      if (isActive) {
        vDot.classList.remove('h-2.5', 'bg-[#CBD5E1]');
        vDot.classList.add('h-7', 'bg-[#012383]');
      } else {
        vDot.classList.remove('h-7', 'bg-[#012383]');
        vDot.classList.add('h-2.5', 'bg-[#CBD5E1]');
      }
    });

    // 4. Synchronize Bottom Journey Step Tabs ('رحلتك مع الأمين')
    this.stepTabs.forEach((tab) => {
      const target = parseInt(tab.getAttribute('data-step-target'), 10);
      const isActive = target === this.currentIndex;
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

    // 5. Update Scroll Cue Label and State
    if (this.scrollCue) {
      const countEl = this.scrollCue.querySelector('.scroll-step-num');
      if (countEl) {
        const arabicNums = ['١', '٢', '٣'];
        const isAr = document.documentElement.lang !== 'en';
        countEl.textContent = isAr ? `${arabicNums[this.currentIndex]} / ٣` : `${this.currentIndex + 1} / 3`;
      }
    }

    // Reset progress bar animation
    this.restartProgressBar();

    // Release transition lock after animation completes
    setTimeout(() => {
      this.isTransitioning = false;
    }, 550);
  }

  next() {
    this.goToSlide(this.currentIndex + 1, 'forward');
  }

  prev() {
    this.goToSlide(this.currentIndex - 1, 'backward');
  }

  restartProgressBar() {
    if (!this.progressBar) return;

    // Reset immediately without animation
    this.progressBar.style.transition = 'none';
    this.progressBar.style.width = '0%';

    // Force layout reflow
    void this.progressBar.offsetWidth;

    // Animate smoothly to 100% across the full delay
    this.progressBar.style.transition = `width ${this.autoPlayDelay}ms linear`;
    this.progressBar.style.width = '100%';
  }

  startAutoPlay() {
    this.stopAutoPlay();
    this.restartProgressBar();

    this.autoPlayTimer = setInterval(() => {
      this.next();
    }, this.autoPlayDelay);
  }

  stopAutoPlay() {
    if (this.autoPlayTimer) {
      clearInterval(this.autoPlayTimer);
      this.autoPlayTimer = null;
    }
    if (this.progressBar) {
      const computedWidth = window.getComputedStyle(this.progressBar).width;
      this.progressBar.style.transition = 'none';
      this.progressBar.style.width = computedWidth;
    }
  }

  bindEvents() {
    // 1. Mouse Wheel / Trackpad Vertical Scroll Handler (Vertical Navigation)
    const handleWheel = (e) => {
      const now = Date.now();
      if (now - this.lastWheelTime < this.wheelCooldown) {
        return;
      }

      // Check vertical scroll threshold
      if (Math.abs(e.deltaY) > 22) {
        this.lastWheelTime = now;
        if (e.deltaY > 0) {
          // Scrolled down -> advance to next slide
          this.next();
        } else {
          // Scrolled up -> go to previous slide
          this.prev();
        }
        this.startAutoPlay();
      }
    };

    // Attach wheel event to window and container with passive option
    window.addEventListener('wheel', handleWheel, { passive: true });

    // 2. Click on Floating Prev / Next Chevron Buttons
    if (this.prevBtn) {
      this.prevBtn.addEventListener('click', () => {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        isRtl ? this.next() : this.prev();
        this.startAutoPlay();
      });
    }

    if (this.nextBtn) {
      this.nextBtn.addEventListener('click', () => {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        isRtl ? this.prev() : this.next();
        this.startAutoPlay();
      });
    }

    // 3. Interactive Vertical Scroll Cue Pill Click
    if (this.scrollCue) {
      this.scrollCue.addEventListener('click', () => {
        this.next();
        this.startAutoPlay();
      });
    }

    // 4. Dot Indicators (Horizontal)
    this.dots.forEach((dot, index) => {
      dot.addEventListener('click', () => {
        const direction = index >= this.currentIndex ? 'forward' : 'backward';
        this.goToSlide(index, direction);
        this.startAutoPlay();
      });
    });

    // 5. Vertical Dot Indicators
    this.verticalDots.forEach((vDot, index) => {
      vDot.addEventListener('click', () => {
        const direction = index >= this.currentIndex ? 'forward' : 'backward';
        this.goToSlide(index, direction);
        this.startAutoPlay();
      });
    });

    // 6. Journey Step Tabs ('رحلتك مع الأمين')
    this.stepTabs.forEach((tab) => {
      tab.addEventListener('click', () => {
        const target = parseInt(tab.getAttribute('data-step-target'), 10);
        if (!isNaN(target)) {
          const direction = target >= this.currentIndex ? 'forward' : 'backward';
          this.goToSlide(target, direction);
          this.startAutoPlay();
        }
      });
    });

    // 7. Pause Auto-play on user hover
    const landingFrame = document.getElementById('landing-frame') || this.container;
    landingFrame.addEventListener('mouseenter', () => this.stopAutoPlay());
    landingFrame.addEventListener('mouseleave', () => this.startAutoPlay());

    // 8. Dual Touch Support (Vertical Swipe & Horizontal Swipe)
    this.container.addEventListener('touchstart', (e) => {
      this.touchStartX = e.changedTouches[0].screenX;
      this.touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });

    this.container.addEventListener('touchend', (e) => {
      this.touchEndX = e.changedTouches[0].screenX;
      this.touchEndY = e.changedTouches[0].screenY;
      this.handleTouchGestures();
    }, { passive: true });

    // 9. Keyboard Arrow Navigation (Up, Down, Left, Right)
    document.addEventListener('keydown', (e) => {
      if (['ArrowDown', 'PageDown'].includes(e.key)) {
        this.next();
        this.startAutoPlay();
      } else if (['ArrowUp', 'PageUp'].includes(e.key)) {
        this.prev();
        this.startAutoPlay();
      } else if (e.key === 'ArrowRight') {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        isRtl ? this.prev() : this.next();
        this.startAutoPlay();
      } else if (e.key === 'ArrowLeft') {
        const isRtl = document.documentElement.getAttribute('dir') === 'rtl';
        isRtl ? this.next() : this.prev();
        this.startAutoPlay();
      }
    });
  }

  handleTouchGestures() {
    const deltaX = this.touchEndX - this.touchStartX;
    const deltaY = this.touchEndY - this.touchStartY;
    const isRtl = document.documentElement.getAttribute('dir') === 'rtl';

    // 1. Dominant Vertical Swipe (Scroll between slides)
    if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaY) > 35) {
      if (deltaY < 0) {
        // Swiped Up -> Move to next slide
        this.next();
      } else {
        // Swiped Down -> Move to previous slide
        this.prev();
      }
      this.startAutoPlay();
      return;
    }

    // 2. Horizontal Swipe
    if (Math.abs(deltaX) > 40) {
      if (deltaX > 0) {
        // Swiped Right
        isRtl ? this.next() : this.prev();
      } else {
        // Swiped Left
        isRtl ? this.prev() : this.next();
      }
      this.startAutoPlay();
    }
  }
}

// Auto instantiate on DOM load
window.addEventListener('DOMContentLoaded', () => {
  window.heroSlider = new HeroSlider();
});

export default HeroSlider;
