/**
 * blogcasha.pl – Main JS
 * Hamburger menu, reading progress, lazy images, copy link, sticky header
 */
(function () {
  'use strict';

  /* ── Hamburger Menu ──────────────────────────────────────── */
  const hamburger = document.getElementById('hamburger');
  const mainNav   = document.getElementById('mainNav');
  const overlay   = document.getElementById('navOverlay');

  function openMenu() {
    mainNav.classList.add('open');
    overlay.classList.add('active');
    hamburger.classList.add('active');
    hamburger.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }
  function closeMenu() {
    mainNav.classList.remove('open');
    overlay.classList.remove('active');
    hamburger.classList.remove('active');
    hamburger.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (hamburger && mainNav) {
    hamburger.addEventListener('click', function () {
      if (mainNav.classList.contains('open')) closeMenu();
      else openMenu();
    });
    overlay && overlay.addEventListener('click', closeMenu);
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && mainNav.classList.contains('open')) closeMenu();
    });
  }

  /* ── Category Dropdown ───────────────────────────────────── */
  const dropdowns = document.querySelectorAll('.nav__item--dropdown');
  dropdowns.forEach(function (item) {
    const toggle = item.querySelector('.nav__dropdown-toggle');
    if (!toggle) return;

    toggle.addEventListener('click', function (e) {
      e.stopPropagation();
      const isOpen = item.classList.contains('open');
      // Close all dropdowns first
      dropdowns.forEach(function (d) {
        d.classList.remove('open');
        const t = d.querySelector('.nav__dropdown-toggle');
        if (t) t.setAttribute('aria-expanded', 'false');
      });
      if (!isOpen) {
        item.classList.add('open');
        toggle.setAttribute('aria-expanded', 'true');
      }
    });
  });

  // Close dropdown on outside click
  document.addEventListener('click', function () {
    dropdowns.forEach(function (item) {
      item.classList.remove('open');
      const t = item.querySelector('.nav__dropdown-toggle');
      if (t) t.setAttribute('aria-expanded', 'false');
    });
  });

  // Close dropdown on Escape
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      dropdowns.forEach(function (item) {
        item.classList.remove('open');
        const t = item.querySelector('.nav__dropdown-toggle');
        if (t) t.setAttribute('aria-expanded', 'false');
      });
    }
  });

  /* ── Sticky Header ───────────────────────────────────────── */
  const header = document.getElementById('siteHeader');
  if (header) {
    const onScroll = () => {
      if (window.scrollY > 80) header.classList.add('scrolled');
      else header.classList.remove('scrolled');
    };
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
  }

  /* ── Reading Progress Bar ────────────────────────────────── */
  const progressBar = document.getElementById('readingProgress');
  if (progressBar) {
    const articleContent = document.getElementById('articleContent');
    if (articleContent) {
      const updateProgress = () => {
        const rect = articleContent.getBoundingClientRect();
        const total = rect.height - window.innerHeight;
        if (total <= 0) { progressBar.style.width = '100%'; return; }
        const scrolled = Math.max(0, -rect.top);
        const pct = Math.min(100, (scrolled / total) * 100);
        progressBar.style.width = pct + '%';
      };
      window.addEventListener('scroll', updateProgress, { passive: true });
      updateProgress();
    }
  }

  /* ── Lazy Image Intersection Observer ───────────────────── */
  if ('IntersectionObserver' in window) {
    const lazyImgs = document.querySelectorAll('img[loading="lazy"]');
    const io = new IntersectionObserver(function (entries, obs) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          const img = entry.target;
          if (img.dataset.src) {
            img.src = img.dataset.src;
            delete img.dataset.src;
          }
          img.classList.add('loaded');
          obs.unobserve(img);
        }
      });
    }, { rootMargin: '200px 0px' });

    lazyImgs.forEach(function (img) { io.observe(img); });
  }

  /* ── Copy Link Button ────────────────────────────────────── */
  const copyBtns = document.querySelectorAll('[data-copy-url]');
  copyBtns.forEach(function (btn) {
    btn.addEventListener('click', function () {
      const url = btn.dataset.copyUrl || window.location.href;
      if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function () {
          showCopied(btn);
        }).catch(function () {
          fallbackCopy(url, btn);
        });
      } else {
        fallbackCopy(url, btn);
      }
    });
  });

  function showCopied(btn) {
    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check" aria-hidden="true"></i> Skopiowano!';
    btn.style.background = '#22c55e';
    btn.style.color = '#fff';
    setTimeout(function () {
      btn.innerHTML = original;
      btn.style.background = '';
      btn.style.color = '';
    }, 2000);
  }

  function fallbackCopy(text, btn) {
    const ta = document.createElement('textarea');
    ta.value = text;
    ta.style.position = 'fixed';
    ta.style.opacity = '0';
    document.body.appendChild(ta);
    ta.focus();
    ta.select();
    try { document.execCommand('copy'); showCopied(btn); } catch (e) {}
    document.body.removeChild(ta);
  }

  /* ── Active Nav Link ─────────────────────────────────────── */
  const currentPath = window.location.pathname;
  const navLinks = document.querySelectorAll('.nav__link');
  navLinks.forEach(function (link) {
    const href = link.getAttribute('href');
    if (href && href !== '/' && currentPath.startsWith(new URL(href, window.location.origin).pathname)) {
      link.style.color = 'var(--color-primary)';
      link.style.fontWeight = '700';
    }
  });

  /* ── Post Card Image Placeholder Color ───────────────────── */
  document.querySelectorAll('.post-card__img--placeholder').forEach(function (el) {
    const color = el.style.background || '#f97316';
    el.setAttribute('aria-label', el.getAttribute('aria-label') || 'Brak zdjęcia');
  });

  /* ── Contact Form Validation ─────────────────────────────── */
  const contactForm = document.querySelector('.contact-form');
  if (contactForm) {
    contactForm.addEventListener('submit', function (e) {
      const name    = contactForm.querySelector('[name="name"]');
      const email   = contactForm.querySelector('[name="email"]');
      const message = contactForm.querySelector('[name="message"]');
      const privacy = contactForm.querySelector('[name="privacy"]');

      let valid = true;
      [name, email, message].forEach(function (field) {
        if (field && !field.value.trim()) {
          field.style.borderColor = '#ef4444';
          valid = false;
        } else if (field) {
          field.style.borderColor = '';
        }
      });

      if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
        email.style.borderColor = '#ef4444';
        valid = false;
      }

      if (privacy && !privacy.checked) {
        privacy.parentElement.style.color = '#ef4444';
        valid = false;
      }

      if (!valid) {
        e.preventDefault();
        const firstError = contactForm.querySelector('[style*="ef4444"]');
        if (firstError) firstError.focus();
      }
    });
  }

  /* ── Smooth anchor scroll ────────────────────────────────── */
  document.querySelectorAll('a[href^="#"]').forEach(function (a) {
    a.addEventListener('click', function (e) {
      const target = document.querySelector(a.getAttribute('href'));
      if (target) {
        e.preventDefault();
        const offset = 80;
        const top = target.getBoundingClientRect().top + window.scrollY - offset;
        window.scrollTo({ top: top, behavior: 'smooth' });
      }
    });
  });

})();
