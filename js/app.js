(() => {
  const storageKey = 'gdw-theme';
  const html = document.documentElement;
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');

  const applyTheme = (theme) => {
    if (theme === 'dark') {
      html.classList.add('dark');
    } else {
      html.classList.remove('dark');
    }
  };

  const storedTheme = localStorage.getItem(storageKey);
  if (storedTheme) {
    applyTheme(storedTheme);
  } else if (prefersDark.matches) {
    applyTheme('dark');
  }

  document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-theme-toggle]');
    const darkIcon = document.querySelector('[data-icon="moon"]');
    const lightIcon = document.querySelector('[data-icon="sun"]');

    const updateToggleState = () => {
      const isDark = html.classList.contains('dark');
      if (toggle) {
        toggle.setAttribute('aria-pressed', String(isDark));
        toggle.setAttribute('aria-label', isDark ? 'Aktifkan mode terang' : 'Aktifkan mode gelap');
      }
      if (darkIcon && lightIcon) {
        darkIcon.classList.toggle('hidden', !isDark);
        lightIcon.classList.toggle('hidden', isDark);
      }
    };

    if (toggle) {
      toggle.addEventListener('click', () => {
        const isDark = html.classList.toggle('dark');
        localStorage.setItem(storageKey, isDark ? 'dark' : 'light');
        updateToggleState();
      });
    }

    prefersDark.addEventListener('change', (event) => {
      if (!localStorage.getItem(storageKey)) {
        applyTheme(event.matches ? 'dark' : 'light');
        updateToggleState();
      }
    });

    updateToggleState();

    const yearSpan = document.querySelector('[data-year]');
    if (yearSpan) {
      yearSpan.textContent = new Date().getFullYear();
    }

    initTestimonialSlider();
  });

  function initTestimonialSlider() {
    const slider = document.querySelector('.testimonial-slider');
    if (!slider) return;

    const slides = Array.from(slider.querySelectorAll('[data-slide]'));
    const dotsContainer = slider.querySelector('[data-dots]');
    const dots = dotsContainer ? Array.from(dotsContainer.querySelectorAll('button')) : [];
    let index = 0;
    let timer = null;
    const interval = 5000;

    const setActive = (nextIndex) => {
      slides.forEach((slide, i) => {
        const isActive = i === nextIndex;
        slide.classList.toggle('is-active', isActive);
        slide.setAttribute('aria-hidden', String(!isActive));
        slide.setAttribute('tabindex', isActive ? '0' : '-1');
      });
      dots.forEach((dot, i) => {
        const isActive = i === nextIndex;
        dot.classList.toggle('bg-[#C8A951]', isActive);
        dot.classList.toggle('bg-neutral-300', !isActive);
        dot.setAttribute('aria-pressed', String(isActive));
      });
      index = nextIndex;
    };

    const next = () => {
      const nextIndex = (index + 1) % slides.length;
      setActive(nextIndex);
    };

    const start = () => {
      stop();
      timer = setInterval(next, interval);
    };

    const stop = () => {
      if (timer) {
        clearInterval(timer);
        timer = null;
      }
    };

    if (dots.length) {
      dots.forEach((dot, dotIndex) => {
        dot.addEventListener('click', () => {
          setActive(dotIndex);
          start();
          slides[dotIndex]?.focus({ preventScroll: true });
        });
      });
    }

    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);

    setActive(0);
    start();
  }
})();
