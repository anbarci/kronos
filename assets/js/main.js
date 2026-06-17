(function () {
	'use strict';

	var root = document.documentElement;
	var STORAGE_KEY = 'kronos-theme';

	function currentTheme() {
		return root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
	}

	function applyTheme(mode) {
		root.setAttribute('data-theme', mode);
		try {
			localStorage.setItem(STORAGE_KEY, mode);
		} catch (e) {}
		document.querySelectorAll('[data-kronos-theme-toggle]').forEach(function (btn) {
			btn.setAttribute('aria-pressed', mode === 'dark' ? 'true' : 'false');
		});
	}

	function openNav() {
		var nav = document.getElementById('kronos-mobile-nav');
		if (!nav) return;
		nav.hidden = false;
		document.body.classList.add('kronos-nav-open');
		document.querySelectorAll('[data-kronos-nav-toggle]').forEach(function (b) {
			b.setAttribute('aria-expanded', 'true');
		});
		var close = nav.querySelector('[data-kronos-nav-close]');
		if (close) close.focus();
	}

	function closeNav() {
		var nav = document.getElementById('kronos-mobile-nav');
		if (!nav) return;
		nav.hidden = true;
		document.body.classList.remove('kronos-nav-open');
		document.querySelectorAll('[data-kronos-nav-toggle]').forEach(function (b) {
			b.setAttribute('aria-expanded', 'false');
		});
	}

	function toggleSearch(open) {
		var ov = document.querySelector('[data-kronos-search]');
		if (!ov) return;
		ov.hidden = !open;
		if (open) {
			var input = ov.querySelector('input[type="search"]');
			if (input) input.focus();
		}
	}

	function closeModals() {
		var any = false;
		document.querySelectorAll('[data-kronos-modal]:not([hidden])').forEach(function (m) {
			m.hidden = true;
			any = true;
		});
		if (any) document.body.classList.remove('kronos-nav-open');
	}

	document.addEventListener('click', function (event) {
		if (event.target.closest('[data-kronos-theme-toggle]')) {
			event.preventDefault();
			applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
			return;
		}
		if (event.target.closest('[data-kronos-totop]')) {
			event.preventDefault();
			var smooth = !window.matchMedia('(prefers-reduced-motion: reduce)').matches;
			window.scrollTo({ top: 0, behavior: smooth ? 'smooth' : 'auto' });
			return;
		}
		var fontBtn = event.target.closest('[data-kronos-font]');
		if (fontBtn) {
			event.preventDefault();
			var dir = fontBtn.getAttribute('data-kronos-font') === 'up' ? 1 : -1;
			var cur = 1;
			try { cur = parseFloat(localStorage.getItem('kronos-reading-scale')) || 1; } catch (e) {}
			var next = Math.min(1.5, Math.max(0.85, Math.round((cur + dir * 0.1) * 100) / 100));
			document.documentElement.style.setProperty('--reading-scale', next);
			try { localStorage.setItem('kronos-reading-scale', next); } catch (e) {}
			return;
		}
		if (event.target.closest('[data-kronos-search-toggle]')) {
			event.preventDefault();
			toggleSearch(true);
			return;
		}
		if (event.target.closest('[data-kronos-search-close]')) {
			event.preventDefault();
			toggleSearch(false);
			return;
		}
		var searchOv = document.querySelector('[data-kronos-search]');
		if (searchOv && !searchOv.hidden && event.target === searchOv) {
			toggleSearch(false);
			return;
		}
		var modalOpen = event.target.closest('[data-kronos-modal-open]');
		if (modalOpen) {
			event.preventDefault();
			var modal = document.querySelector('[data-kronos-modal="' + modalOpen.getAttribute('data-kronos-modal-open') + '"]');
			if (modal) {
				modal.hidden = false;
				document.body.classList.add('kronos-nav-open');
				var mc = modal.querySelector('[data-kronos-modal-close]');
				if (mc) mc.focus();
			}
			return;
		}
		if (event.target.closest('[data-kronos-modal-close]')) {
			event.preventDefault();
			closeModals();
			return;
		}
		var openModalEl = event.target.closest('[data-kronos-modal]');
		if (openModalEl && event.target === openModalEl) {
			closeModals();
			return;
		}
		if (event.target.closest('[data-kronos-nav-toggle]')) {
			event.preventDefault();
			openNav();
			return;
		}
		if (event.target.closest('[data-kronos-nav-close]')) {
			event.preventDefault();
			closeNav();
			return;
		}
		var copyBtn = event.target.closest('[data-kronos-copy]');
		if (copyBtn) {
			event.preventDefault();
			var url = copyBtn.getAttribute('data-kronos-copy');
			var done = function () {
				copyBtn.classList.add('is-copied');
				setTimeout(function () { copyBtn.classList.remove('is-copied'); }, 1800);
			};
			if (navigator.clipboard && navigator.clipboard.writeText) {
				navigator.clipboard.writeText(url).then(done).catch(done);
			} else {
				var t = document.createElement('textarea');
				t.value = url; document.body.appendChild(t); t.select();
				try { document.execCommand('copy'); } catch (e) {}
				document.body.removeChild(t); done();
			}
			return;
		}
		var feedBtn = event.target.closest('[data-kronos-feed-prev], [data-kronos-feed-next]');
		if (feedBtn) {
			event.preventDefault();
			var section = feedBtn.closest('section');
			var track = section && section.querySelector('[data-kronos-feed-track]');
			if (track) {
				var dir = feedBtn.hasAttribute('data-kronos-feed-next') ? 1 : -1;
				track.scrollBy({ left: dir * Math.round(track.clientWidth * 0.8), behavior: 'smooth' });
			}
			return;
		}
		var embedBtn = event.target.closest('[data-kronos-embed] .kronos-embed__play');
		if (embedBtn) {
			event.preventDefault();
			var embed = embedBtn.closest('[data-kronos-embed]');
			var id = embed.getAttribute('data-id');
			if (id) {
				var iframe = document.createElement('iframe');
				iframe.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1';
				iframe.setAttribute('allow', 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture');
				iframe.setAttribute('allowfullscreen', '');
				iframe.setAttribute('title', 'YouTube');
				embed.innerHTML = '';
				embed.appendChild(iframe);
			}
			return;
		}
		var nav = document.getElementById('kronos-mobile-nav');
		if (nav && !nav.hidden && event.target === nav) {
			closeNav();
		}
	});

	document.addEventListener('keydown', function (event) {
		if (event.key === 'Escape') {
			closeNav();
			toggleSearch(false);
			closeModals();
		}
	});

	function initProgress() {
		var bar = document.querySelector('[data-kronos-progress]');
		if (!bar) return;
		var ticking = false;
		var update = function () {
			ticking = false;
			var h = document.documentElement;
			var max = h.scrollHeight - h.clientHeight;
			var pct = max > 0 ? (h.scrollTop / max) * 100 : 0;
			bar.style.width = pct + '%';
		};
		window.addEventListener('scroll', function () {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		}, { passive: true });
		update();
	}

	function initToTop() {
		var btn = document.querySelector('[data-kronos-totop]');
		if (!btn) return;
		var ticking = false;
		var update = function () {
			ticking = false;
			btn.classList.toggle('is-visible', window.scrollY > 600);
		};
		window.addEventListener('scroll', function () {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		}, { passive: true });
		update();
	}

	function initReveal() {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
		if (!('IntersectionObserver' in window)) return;
		var els = document.querySelectorAll('.kronos-card, .kronos-hero__main, .kronos-hero__item, .kronos-block, .kronos-newsletter');
		if (!els.length) return;
		var io = new IntersectionObserver(function (entries) {
			entries.forEach(function (e) {
				if (e.isIntersecting) { e.target.classList.add('kronos-in'); io.unobserve(e.target); }
			});
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.06 });
		els.forEach(function (el, i) {
			el.classList.add('kronos-reveal');
			el.style.transitionDelay = (Math.min(i % 6, 5) * 60) + 'ms';
			io.observe(el);
		});
	}

	function initFontSize() {
		try {
			var s = parseFloat(localStorage.getItem('kronos-reading-scale'));
			if (s >= 0.85 && s <= 1.5) {
				document.documentElement.style.setProperty('--reading-scale', s);
			}
		} catch (e) {}
	}

	document.addEventListener('DOMContentLoaded', function () {
		applyTheme(currentTheme());
		initProgress();
		initReveal();
		initToTop();
		initFontSize();
	});
})();
