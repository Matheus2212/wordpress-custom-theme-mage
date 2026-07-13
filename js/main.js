(function () {
	'use strict';

	// ── Mobile Menu ────────────────────────────────────────────────────────────
	var toggle = document.querySelector('.menu-toggle');
	var mobileNav = document.getElementById('mobile-navigation');
	var body = document.body;

	if (toggle && mobileNav) {
		toggle.addEventListener('click', function () {
			var expanded = toggle.getAttribute('aria-expanded') === 'true';
			toggle.setAttribute('aria-expanded', String(!expanded));
			toggle.classList.toggle('active');
			mobileNav.classList.toggle('open');
			mobileNav.setAttribute('aria-hidden', String(expanded));
			body.style.overflow = expanded ? '' : 'hidden';
		});

		// Close on outside click
		document.addEventListener('click', function (e) {
			if (mobileNav.classList.contains('open') && !mobileNav.contains(e.target) && !toggle.contains(e.target)) {
				toggle.setAttribute('aria-expanded', 'false');
				toggle.classList.remove('active');
				mobileNav.classList.remove('open');
				mobileNav.setAttribute('aria-hidden', 'true');
				body.style.overflow = '';
			}
		});

		// Close on ESC
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && mobileNav.classList.contains('open')) {
				toggle.setAttribute('aria-expanded', 'false');
				toggle.classList.remove('active');
				mobileNav.classList.remove('open');
				mobileNav.setAttribute('aria-hidden', 'true');
				body.style.overflow = '';
				toggle.focus();
			}
		});
	}

	// ── Header scroll shadow ───────────────────────────────────────────────────
	var header = document.getElementById('masthead');
	if (header) {
		var onScroll = function () {
			if (window.scrollY > 10) {
				header.classList.add('scrolled');
			} else {
				header.classList.remove('scrolled');
			}
		};
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	// ── Animate on scroll (simple IntersectionObserver) ────────────────────────
	if ('IntersectionObserver' in window) {
		var els = document.querySelectorAll('[data-anim]');
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('anim-fade-up');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.1 });
		els.forEach(function (el) { observer.observe(el); });
	}

	// ── Testimonials carousel ───────────────────────────────────────────────────
	document.querySelectorAll('[data-mage-carousel]').forEach(function (carousel) {
		var track = carousel.querySelector('.mage-carousel__track');
		var slides = carousel.querySelectorAll('.mage-testimonial');
		if (!track || slides.length === 0) { return; }

		var index = 0;
		var go = function (i) {
			index = (i + slides.length) % slides.length;
			track.style.transform = 'translateX(' + (-index * 100) + '%)';
			slides.forEach(function (slide, si) {
				slide.setAttribute('aria-hidden', si === index ? 'false' : 'true');
			});
		};

		var prev = carousel.querySelector('.mage-carousel__prev');
		var next = carousel.querySelector('.mage-carousel__next');
		if (prev) { prev.addEventListener('click', function () { go(index - 1); }); }
		if (next) { next.addEventListener('click', function () { go(index + 1); }); }

		if (slides.length < 2) {
			if (prev) { prev.style.display = 'none'; }
			if (next) { next.style.display = 'none'; }
			go(0);
			return;
		}

		go(0);

		var timer = setInterval(function () { go(index + 1); }, 7000);
		var stop = function () { clearInterval(timer); };
		carousel.addEventListener('mouseenter', stop);
		carousel.addEventListener('focusin', stop);
		carousel.addEventListener('touchstart', stop, { passive: true });
	});
})();
