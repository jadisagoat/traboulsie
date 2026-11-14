/* Traboulsi Est. — option 2 (industrial design) */
(function () {
	'use strict';

	var doc = document;
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var hasIO = 'IntersectionObserver' in window;

	function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
	function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

	/* ---------- Header, scroll progress, back to top, parallax ---------- */
	var header = $('#siteHeader');
	var bar = $('#scrollBar');
	var toTop = $('#toTop');
	var ring = $('#topRing');
	var parallax = reduceMotion ? [] : $$('[data-parallax]');
	var ticking = false;

	function onScroll() {
		var y = window.pageYOffset || doc.documentElement.scrollTop;
		var max = doc.documentElement.scrollHeight - window.innerHeight;
		var progress = max > 0 ? Math.min(1, y / max) : 0;
		if (header) header.classList.toggle('is-fixed', y > header.offsetHeight + 160);
		if (bar) bar.style.transform = 'scaleX(' + progress + ')';
		if (toTop) toTop.classList.toggle('is-visible', y > 700);
		if (ring) ring.style.strokeDashoffset = (138.2 * (1 - progress)).toFixed(1);
		parallax.forEach(function (img) {
			var box = img.parentNode.getBoundingClientRect();
			if (box.bottom < 0 || box.top > window.innerHeight) return;
			var shift = (box.top + box.height / 2 - window.innerHeight / 2) / window.innerHeight;
			img.style.transform = 'translate3d(0,' + (shift * -60 - 30).toFixed(1) + 'px,0)';
		});
		ticking = false;
	}
	window.addEventListener('scroll', function () {
		if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
	}, { passive: true });
	window.addEventListener('resize', onScroll);
	onScroll();

	/* ---------- Full screen menu ---------- */
	var menu = $('#menu');
	var menuToggle = $('#menuToggle');
	function setMenu(open) {
		if (!menu || !menuToggle) return;
		if (open) {
			menu.hidden = false;
			requestAnimationFrame(function () { menu.classList.add('is-open'); });
		} else if (!menu.hidden) {
			menu.classList.remove('is-open');
			setTimeout(function () { if (!menu.classList.contains('is-open')) menu.hidden = true; }, 700);
		}
		menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		menuToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		doc.body.classList.toggle('is-locked', open);
	}
	if (menuToggle) {
		menuToggle.addEventListener('click', function () { setMenu(menuToggle.getAttribute('aria-expanded') !== 'true'); });
		doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });
	}

	/* ---------- In-page links ----------
	   The page sets <base href>, so "#about" would otherwise resolve to base_url#about
	   and reload the page when it is opened through /option2. */
	function scrollToId(id) {
		var target = doc.getElementById(id);
		if (!target) return false;
		target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
		if (history.replaceState) history.replaceState(null, '', location.pathname + location.search + '#' + id);
		return true;
	}
	doc.addEventListener('click', function (e) {
		var link = e.target.closest('a[href^="#"]');
		if (!link) return;
		var id = link.getAttribute('href').slice(1);
		if (id && scrollToId(id)) {
			e.preventDefault();
			setMenu(false);
		}
	});

	/* ---------- Active nav link ---------- */
	var navLinks = $$('.nav a[href^="#"]');
	if (hasIO && navLinks.length) {
		var linkFor = {};
		navLinks.forEach(function (l) { linkFor[l.getAttribute('href').slice(1)] = l; });
		var spy = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				navLinks.forEach(function (l) { l.classList.remove('is-active'); });
				linkFor[entry.target.id].classList.add('is-active');
			});
		}, { rootMargin: '-45% 0px -50% 0px' });
		Object.keys(linkFor).forEach(function (id) {
			var section = doc.getElementById(id);
			if (section) spy.observe(section);
		});
	}

	/* ---------- Hero slider ---------- */
	var slides = $$('.hero-slide');
	var dots = $$('.hero-dot');
	var heroNow = $('#heroNow');
	var current = 0;
	var timer = null;
	function showSlide(i) {
		if (!slides.length) return;
		current = (i + slides.length) % slides.length;
		slides.forEach(function (s, j) { s.classList.toggle('is-active', j === current); });
		dots.forEach(function (d, j) {
			d.classList.remove('is-active');
			d.classList.toggle('is-done', j < current);
		});
		if (dots[current]) {
			void dots[current].offsetWidth; // restart the progress line
			dots[current].classList.add('is-active');
		}
		if (heroNow) heroNow.textContent = (current < 9 ? '0' : '') + (current + 1);
	}
	function play() {
		clearInterval(timer);
		if (slides.length > 1 && !reduceMotion) timer = setInterval(function () { showSlide(current + 1); }, 7000);
	}
	dots.forEach(function (dot, i) {
		dot.addEventListener('click', function () { showSlide(i); play(); });
	});
	doc.addEventListener('visibilitychange', function () {
		if (doc.hidden) clearInterval(timer); else play();
	});
	play();

	/* ---------- Reveal on scroll ---------- */
	var reveals = $$('[data-reveal]');
	if (hasIO && !reduceMotion) {
		var revealer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-visible');
				revealer.unobserve(entry.target);
			});
		}, { rootMargin: '0px 0px -8% 0px' });
		reveals.forEach(function (el) { revealer.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* ---------- Counters ---------- */
	function countUp(el) {
		var end = parseInt(el.getAttribute('data-count'), 10) || 0;
		var start = null;
		function step(t) {
			if (start === null) start = t;
			var p = Math.min(1, (t - start) / 1800);
			el.textContent = Math.round(end * (1 - Math.pow(1 - p, 4)));
			if (p < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	if (hasIO && !reduceMotion) {
		var counter = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				countUp(entry.target);
				counter.unobserve(entry.target);
			});
		}, { threshold: .6 });
		$$('[data-count]').forEach(function (el) { counter.observe(el); });
	}

	/* ---------- About "read more" ---------- */
	var aboutText = $('#aboutText');
	var aboutBtn = $('[data-toggle-about]');
	if (aboutText && aboutBtn) {
		if (aboutText.scrollHeight <= aboutText.clientHeight + 8) {
			aboutText.classList.remove('is-clamped');
			aboutBtn.hidden = true;
		}
		aboutBtn.addEventListener('click', function () {
			var open = aboutText.classList.contains('is-clamped');
			aboutText.classList.toggle('is-clamped', !open);
			aboutBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
			$('span', aboutBtn).textContent = open ? 'Read less' : 'Read more';
		});
	}

	/* ---------- Client tabs ---------- */
	$$('[data-tabs]').forEach(function (wrap) {
		var tabs = $$('[role="tab"]', wrap);
		var panels = $$('[role="tabpanel"]', wrap);
		function select(i, focus) {
			tabs.forEach(function (tab, j) {
				var on = i === j;
				tab.classList.toggle('is-active', on);
				tab.setAttribute('aria-selected', on ? 'true' : 'false');
				tab.tabIndex = on ? 0 : -1;
				if (panels[j]) panels[j].classList.toggle('is-active', on);
			});
			if (focus) tabs[i].focus();
		}
		tabs.forEach(function (tab, i) {
			tab.addEventListener('click', function () { select(i); });
			tab.addEventListener('keydown', function (e) {
				var n = tabs.length;
				if (e.key === 'ArrowDown' || e.key === 'ArrowRight') { e.preventDefault(); select((i + 1) % n, true); }
				if (e.key === 'ArrowUp' || e.key === 'ArrowLeft') { e.preventDefault(); select((i - 1 + n) % n, true); }
			});
		});
	});

	/* ---------- Ask about a product: fill the subject and open the form ---------- */
	function askAbout(name) {
		var subject = $('#subjectField');
		if (subject) subject.value = 'Inquiry about: ' + name;
		scrollToId('contact');
		var nameField = $('#main-contact-form input[name="name"]');
		if (nameField) setTimeout(function () { nameField.focus({ preventScroll: true }); }, 800);
	}

	/* ---------- Products: filter, search, load more ---------- */
	var grid = $('#productGrid');
	var matches = [];
	if (grid) {
		var PAGE = 12;
		var cards = $$('.p-card', grid);
		var tabs = $$('.tab[data-filter]');
		var search = $('#productSearch');
		var moreBtn = $('#loadMore');
		var moreWrap = $('#loadMoreWrap');
		var shownEl = $('#shownCount');
		var totalEl = $('#totalCount');
		var progress = $('#productsBar');
		var emptyEl = $('#productsEmpty');
		var filter = '*';
		var query = '';
		var limit = PAGE;
		var shownBefore = 0;

		var render = function (append) {
			matches = cards.filter(function (card) {
				return (filter === '*' || card.getAttribute('data-cat') === filter) &&
					(!query || card.getAttribute('data-search').indexOf(query) !== -1);
			});
			cards.forEach(function (card) { card.classList.remove('is-shown'); });
			matches.forEach(function (card, i) {
				if (i >= limit) return;
				// only the new cards are staggered, the others stay still
				card.style.setProperty('--n', append ? Math.max(0, i - shownBefore) : i % PAGE);
				if (append && i < shownBefore) card.style.animation = 'none';
				else card.style.animation = '';
				card.classList.add('is-shown');
			});

			var shown = Math.min(limit, matches.length);
			shownBefore = shown;
			shownEl.textContent = shown;
			totalEl.textContent = matches.length;
			progress.style.width = matches.length ? (shown / matches.length * 100) + '%' : '0';
			moreBtn.hidden = shown >= matches.length;
			moreWrap.hidden = matches.length === 0;
			emptyEl.hidden = matches.length > 0;
		};

		var setFilter = function (value) {
			filter = value;
			limit = PAGE;
			tabs.forEach(function (tab) {
				var on = tab.getAttribute('data-filter') === value;
				tab.classList.toggle('is-active', on);
				tab.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			render(false);
		};

		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () { setFilter(tab.getAttribute('data-filter')); });
		});
		if (search) {
			search.addEventListener('input', function () {
				query = search.value.trim().toLowerCase();
				limit = PAGE;
				render(false);
			});
		}
		moreBtn.addEventListener('click', function () {
			limit += PAGE;
			render(true);
		});
		// solution cards and footer links pick a category before scrolling to the catalogue
		doc.addEventListener('click', function (e) {
			var link = e.target.closest('[data-filter-link]');
			if (!link) return;
			if (search) { search.value = ''; query = ''; }
			setFilter(link.getAttribute('data-filter-link'));
		}, true);

		grid.addEventListener('click', function (e) {
			var ask = e.target.closest('[data-ask]');
			if (ask) {
				askAbout(ask.closest('.p-card').getAttribute('data-name'));
				return;
			}
			var media = e.target.closest('.p-media');
			if (!media) return;
			e.preventDefault();
			lbOpen(productItems(), matches.indexOf(media.closest('.p-card')), false);
		});

		grid.classList.add('is-ready');
		render(false);
	}

	function productItems() {
		return matches.map(function (card) {
			return {
				src: $('.p-media', card).getAttribute('href'),
				title: card.getAttribute('data-name'),
				cat: $('.p-cat', card).textContent
			};
		});
	}

	/* ---------- Lightbox (products and showroom photos) ---------- */
	var lb = $('#lightbox');
	var lbImg = $('#lbImg');
	var lbTitle = $('#lbTitle');
	var lbCat = $('#lbCat');
	var lbCount = $('#lbCount');
	var lbAsk = $('#lbAsk');
	var items = [];
	var lbIndex = 0;
	var lastFocus = null;

	function lbShow(i) {
		if (!items.length) return;
		lbIndex = (i + items.length) % items.length;
		var item = items[lbIndex];
		lb.classList.add('is-loading');
		lbImg.onload = lbImg.onerror = function () { lb.classList.remove('is-loading'); };
		lbImg.src = item.src;
		lbImg.alt = item.title;
		lbTitle.textContent = item.title;
		lbCat.textContent = item.cat;
		lbCount.textContent = (lbIndex < 9 ? '0' : '') + (lbIndex + 1) + ' / ' + (items.length < 10 ? '0' : '') + items.length;
	}
	function lbOpen(list, index, isPhoto) {
		if (!lb || !list.length) return;
		items = list;
		lastFocus = doc.activeElement;
		lb.classList.toggle('is-photo', !!isPhoto);
		lb.hidden = false;
		doc.body.classList.add('is-locked');
		lbShow(Math.max(0, index));
		requestAnimationFrame(function () { lb.classList.add('is-open'); });
		$('.lb-close', lb).focus();
	}
	function lbClose(restoreFocus) {
		lb.classList.remove('is-open');
		doc.body.classList.remove('is-locked');
		setTimeout(function () { lb.hidden = true; lbImg.removeAttribute('src'); }, 300);
		if (restoreFocus !== false && lastFocus) lastFocus.focus({ preventScroll: true });
	}

	if (lb) {
		var photos = $$('[data-gallery]');
		photos.forEach(function (link, i) {
			link.addEventListener('click', function (e) {
				e.preventDefault();
				lbOpen(photos.map(function (p) {
					return { src: p.getAttribute('href'), title: $('img', p).getAttribute('alt'), cat: 'Showroom' };
				}), i, true);
			});
		});

		$('.lb-close', lb).addEventListener('click', lbClose);
		$('.lb-prev', lb).addEventListener('click', function () { lbShow(lbIndex - 1); });
		$('.lb-next', lb).addEventListener('click', function () { lbShow(lbIndex + 1); });
		lb.addEventListener('click', function (e) {
			if (e.target === lb || e.target.classList.contains('lb-stage')) lbClose();
		});
		lbAsk.addEventListener('click', function () {
			lbClose(false);
			askAbout(lbTitle.textContent);
		});

		doc.addEventListener('keydown', function (e) {
			if (lb.hidden) return;
			if (e.key === 'Escape') lbClose();
			else if (e.key === 'ArrowLeft') lbShow(lbIndex - 1);
			else if (e.key === 'ArrowRight') lbShow(lbIndex + 1);
			else if (e.key === 'Tab') {
				var focusables = $$('button', lb).filter(function (b) { return b.offsetParent !== null; });
				var first = focusables[0];
				var last = focusables[focusables.length - 1];
				if (e.shiftKey && doc.activeElement === first) { e.preventDefault(); last.focus(); }
				else if (!e.shiftKey && doc.activeElement === last) { e.preventDefault(); first.focus(); }
			}
		});

		var touchX = null;
		lb.addEventListener('touchstart', function (e) { touchX = e.touches[0].clientX; }, { passive: true });
		lb.addEventListener('touchend', function (e) {
			if (touchX === null) return;
			var dx = e.changedTouches[0].clientX - touchX;
			if (Math.abs(dx) > 50) lbShow(lbIndex + (dx < 0 ? 1 : -1));
			touchX = null;
		});
	}

	/* ---------- Scroll to the form after it was sent ---------- */
	if ($('#main-contact-form .alert')) scrollToId('contact');
})();
