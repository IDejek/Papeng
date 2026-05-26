/**
 * DPW PSI Papua Pegunungan — Main JavaScript
 * Version: 1.0.0
 */
(function() {
    'use strict';

    /* ─── Preloader ─── */
    window.addEventListener('load', function() {
        var preloader = document.getElementById('dpw-preloader');
        if (preloader) {
            setTimeout(function() { preloader.classList.add('loaded'); }, 300);
            setTimeout(function() { preloader.style.display = 'none'; }, 900);
        }
    });

    /* ─── Sticky Navbar ─── */
    var navbar = document.getElementById('dpw-navbar');
    if (navbar) {
        var lastScroll = 0;
        window.addEventListener('scroll', function() {
            var st = window.pageYOffset || document.documentElement.scrollTop;
            if (st > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            lastScroll = st;
        }, { passive: true });
    }

    /* ─── Mobile Menu ─── */
    var hamburger = document.getElementById('dpw-hamburger');
    var mobileMenu = document.getElementById('dpw-mobile-menu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('open');
            document.body.classList.toggle('menu-open');
            hamburger.setAttribute('aria-expanded', mobileMenu.classList.contains('open'));
        });

        mobileMenu.addEventListener('click', function(e) {
            if (e.target === mobileMenu) {
                hamburger.classList.remove('active');
                mobileMenu.classList.remove('open');
                document.body.classList.remove('menu-open');
            }
        });
    }

    /* ─── Hero Slider ─── */
    var heroSlides = document.querySelectorAll('.dpw-hero-slide');
    var heroDots = document.querySelectorAll('.dpw-hero-dot');
    var heroPrev = document.querySelector('.dpw-hero-prev');
    var heroNext = document.querySelector('.dpw-hero-next');

    if (heroSlides.length > 1) {
        var currentSlide = 0;
        var slideInterval;
        var slideDelay = 6000;

        function goToSlide(index) {
            heroSlides[currentSlide].classList.remove('active');
            if (heroDots[currentSlide]) heroDots[currentSlide].classList.remove('active');
            currentSlide = (index + heroSlides.length) % heroSlides.length;
            heroSlides[currentSlide].classList.add('active');
            if (heroDots[currentSlide]) heroDots[currentSlide].classList.add('active');
        }

        function startSlider() {
            slideInterval = setInterval(function() { goToSlide(currentSlide + 1); }, slideDelay);
        }
        function resetSlider() {
            clearInterval(slideInterval);
            startSlider();
        }

        heroDots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                goToSlide(parseInt(this.getAttribute('data-slide')));
                resetSlider();
            });
        });

        if (heroPrev) {
            heroPrev.addEventListener('click', function() { goToSlide(currentSlide - 1); resetSlider(); });
        }
        if (heroNext) {
            heroNext.addEventListener('click', function() { goToSlide(currentSlide + 1); resetSlider(); });
        }

        /* Touch support */
        var heroEl = document.getElementById('dpw-hero');
        if (heroEl) {
            var touchStartX = 0;
            heroEl.addEventListener('touchstart', function(e) { touchStartX = e.changedTouches[0].screenX; }, { passive: true });
            heroEl.addEventListener('touchend', function(e) {
                var diff = touchStartX - e.changedTouches[0].screenX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) goToSlide(currentSlide + 1);
                    else goToSlide(currentSlide - 1);
                    resetSlider();
                }
            }, { passive: true });
        }

        startSlider();
    }

    /* ─── Statistics Counter ─── */
    var statValues = document.querySelectorAll('.dpw-stat-value[data-count]');
    if (statValues.length > 0) {
        var statsAnimated = false;

        function animateCounters() {
            if (statsAnimated) return;
            var firstStat = statValues[0].getBoundingClientRect();
            if (firstStat.top < window.innerHeight && firstStat.bottom > 0) {
                statsAnimated = true;
                statValues.forEach(function(el) {
                    var target = parseInt(el.getAttribute('data-count')) || 0;
                    var duration = 2000;
                    var start = 0;
                    var startTime = null;

                    function step(timestamp) {
                        if (!startTime) startTime = timestamp;
                        var progress = Math.min((timestamp - startTime) / duration, 1);
                        var eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = Math.floor(eased * target).toLocaleString('id-ID');
                        if (progress < 1) requestAnimationFrame(step);
                        else el.textContent = target.toLocaleString('id-ID');
                    }
                    requestAnimationFrame(step);
                });
            }
        }

        window.addEventListener('scroll', animateCounters, { passive: true });
        animateCounters();
    }

    /* ─── Scroll Animations ─── */
    var animateElements = document.querySelectorAll('.dpw-org-card, .dpw-dpd-card, .dpw-news-card, .dpw-video-card, .dpw-gallery-item, .dpw-stat-card, .dpw-social-card');
    if (animateElements.length > 0 && 'IntersectionObserver' in window) {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -30px 0px' });

        animateElements.forEach(function(el) {
            el.style.opacity = '0';
            el.style.transform = 'translateY(20px)';
            el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(el);
        });
    }

    /* ─── Video Play Button (lazy embed) ─── */
    document.querySelectorAll('.dpw-video-embed').forEach(function(el) {
        el.addEventListener('click', function() {
            var src = this.getAttribute('data-src');
            if (src) {
                this.innerHTML = '<iframe src="' + src + '?autoplay=1" frameborder="0" allowfullscreen style="position:absolute;inset:0;width:100%;height:100%;"></iframe>';
                this.style.position = 'relative';
                this.removeAttribute('data-src');
            }
        });
    });

    /* ─── Contact Form AJAX ─── */
    var contactForm = document.getElementById('dpw-contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            var btnText = document.querySelector('#dpw-contact-submit .dpw-btn-text');
            var btnLoading = document.querySelector('#dpw-contact-submit .dpw-btn-loading');
            var resultDiv = document.getElementById('dpw-contact-result');

            if (btnText) btnText.style.display = 'none';
            if (btnLoading) btnLoading.style.display = 'inline-flex';

            var formData = new FormData();
            formData.append('action', 'dpw_contact');
            formData.append('nonce', dpwAjax.nonce);
            formData.append('name', contactForm.querySelector('[name="name"]').value);
            formData.append('email', contactForm.querySelector('[name="email"]').value);
            formData.append('subject', contactForm.querySelector('[name="subject"]').value);
            formData.append('message', contactForm.querySelector('[name="message"]').value);
            formData.append('captcha_answer', contactForm.querySelector('[name="captcha_answer"]').value);
            formData.append('captcha_expected', contactForm.querySelector('[name="captcha_expected"]').value);

            fetch(dpwAjax.ajaxurl, {
                method: 'POST',
                body: formData
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                if (btnText) btnText.style.display = 'inline';
                if (btnLoading) btnLoading.style.display = 'none';
                if (resultDiv) {
                    resultDiv.style.display = 'block';
                    /* Clear previous result safely */
                    resultDiv.innerHTML = '';

                    if (data.success) {
                        var successBox = document.createElement('div');
                        successBox.style.cssText = 'padding:1rem;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;color:#166534;font-size:0.9rem;';
                        successBox.textContent = data.data.message; /* textContent prevents DOM XSS */
                        resultDiv.appendChild(successBox);
                        contactForm.reset();
                    } else {
                        var errorBox = document.createElement('div');
                        errorBox.style.cssText = 'padding:1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#991b1b;font-size:0.9rem;';
                        errorBox.textContent = data.data.message; /* textContent prevents DOM XSS */
                        resultDiv.appendChild(errorBox);
                    }
                }
            })
            .catch(function() {
                if (btnText) btnText.style.display = 'inline';
                if (btnLoading) btnLoading.style.display = 'none';
                if (resultDiv) {
                    resultDiv.style.display = 'block';
                    resultDiv.innerHTML = '';
                    var catchBox = document.createElement('div');
                    catchBox.style.cssText = 'padding:1rem;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#991b1b;font-size:0.9rem;';
                    catchBox.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
                    resultDiv.appendChild(catchBox);
                }
            });
        });
    }

    /* ─── Smooth Scroll for Anchor Links ─── */
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ─── Back to Top ─── */
    var backToTop = document.createElement('button');
    backToTop.className = 'dpw-back-to-top';
    backToTop.innerHTML = '<i class="bi bi-chevron-up"></i>';
    backToTop.setAttribute('aria-label', 'Kembali ke atas');
    backToTop.style.cssText = 'position:fixed;bottom:5.5rem;right:1.5rem;z-index:998;width:44px;height:44px;border-radius:50%;background:var(--psi-dark);color:#fff;border:none;cursor:pointer;font-size:1.1rem;display:none;align-items:center;justify-content:center;box-shadow:0 2px 12px rgba(0,0,0,0.2);transition:all 0.3s ease;opacity:0;';
    document.body.appendChild(backToTop);

    backToTop.addEventListener('click', function() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 400) {
            backToTop.style.display = 'flex';
            setTimeout(function() { backToTop.style.opacity = '1'; }, 10);
        } else {
            backToTop.style.opacity = '0';
            setTimeout(function() { backToTop.style.display = 'none'; }, 300);
        }
    }, { passive: true });

})();
