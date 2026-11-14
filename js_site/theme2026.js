/* Traboulsi Est. — 2026 theme */
(function () {
	'use strict';

	var doc = document;
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var hasIO = 'IntersectionObserver' in window;

	function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
	function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

	/* ---------- Header state + back to top ---------- */
	var header = $('#siteHeader');
	var toTop = $('#toTop');
	function onScroll() {
		var y = window.pageYOffset || doc.documentElement.scrollTop;
		if (header) header.classList.toggle('is-scrolled', y > 8);
		if (toTop) toTop.classList.toggle('is-visible', y > 700);
	}
	window.addEventListener('scroll', onScroll, { passive: true });
	onScroll();

	/* ---------- Mobile navigation ---------- */
	var nav = $('#mainNav');
	var navToggle = $('#navToggle');
	function setNav(open) {
		if (!nav || !navToggle) return;
		nav.classList.toggle('is-open', open);
		navToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		navToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		doc.body.classList.toggle('nav-open', open);
	}
	if (navToggle) {
		navToggle.addEventListener('click', function () { setNav(!nav.classList.contains('is-open')); });
		doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') setNav(false); });
	}

	/* ---------- In-page links ----------
	   The page sets <base href>, so "#about" would otherwise resolve to base_url#about
	   and reload the page when it is opened through index.php/home. */
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
			setNav(false);
		}
	});

	/* ---------- Active nav link ---------- */
	var navLinks = $$('#mainNav a[href^="#"]:not(.btn)');
	if (hasIO && navLinks.length) {
		var linkFor = {};
		navLinks.forEach(function (l) { linkFor[l.getAttribute('href').slice(1)] = l; });
		var spy = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting || !linkFor[entry.target.id]) return;
				navLinks.forEach(function (l) { l.classList.remove('is-active'); });
				linkFor[entry.target.id].classList.add('is-active');
			});
		}, { rootMargin: '-45% 0px -50% 0px' });
		Object.keys(linkFor).forEach(function (id) {
			var section = doc.getElementById(id);
			if (section) spy.observe(section);
		});
	}

	/* ---------- Hero slideshow ---------- */
	var slides = $$('.hero-slide');
	var dots = $$('.hero-dot');
	var current = 0;
	var timer = null;
	function showSlide(i) {
		if (!slides.length) return;
		slides[current].classList.remove('is-active');
		if (dots[current]) dots[current].classList.remove('is-active');
		current = (i + slides.length) % slides.length;
		slides[current].classList.add('is-active');
		if (dots[current]) dots[current].classList.add('is-active');
	}
	function play() {
		clearInterval(timer);
		if (slides.length > 1 && !reduceMotion) timer = setInterval(function () { showSlide(current + 1); }, 6000);
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
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
		reveals.forEach(function (el) { revealer.observe(el); });
	} else {
		reveals.forEach(function (el) { el.classList.add('is-visible'); });
	}

	/* ---------- Count-up numbers ---------- */
	function countUp(el) {
		var end = parseInt(el.getAttribute('data-count'), 10) || 0;
		var start = null;
		function step(t) {
			if (start === null) start = t;
			var p = Math.min((t - start) / 1400, 1);
			el.textContent = Math.round(end * (1 - Math.pow(1 - p, 3)));
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
		}, { threshold: 0.6 });
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

	/* ---------- Products: filter, search, load more ---------- */
	var grid = $('#productGrid');
	if (!grid) return;

	var PAGE = 12;
	var cards = $$('.product-card', grid);
	var chips = $$('.chip[data-filter]');
	var search = $('#productSearch');
	var moreBtn = $('#loadMore');
	var moreWrap = $('#loadMoreWrap');
	var shownEl = $('#shownCount');
	var totalEl = $('#totalCount');
	var bar = $('#productsBar');
	var emptyEl = $('#productsEmpty');
	var filter = '*';
	var query = '';
	var limit = PAGE;
	var matches = [];

	function render() {
		matches = cards.filter(function (card) {
			return (filter === '*' || card.getAttribute('data-cat') === filter) &&
				(!query || card.getAttribute('data-search').indexOf(query) !== -1);
		});
		cards.forEach(function (card) { card.classList.remove('is-shown'); });
		matches.forEach(function (card, i) { if (i < limit) card.classList.add('is-shown'); });

		var shown = Math.min(limit, matches.length);
		shownEl.textContent = shown;
		totalEl.textContent = matches.length;
		bar.style.width = matches.length ? (shown / matches.length * 100) + '%' : '0';
		moreBtn.hidden = shown >= matches.length;
		moreWrap.hidden = matches.length === 0;
		emptyEl.hidden = matches.length > 0;
	}

	function setFilter(value) {
		filter = value;
		limit = PAGE;
		chips.forEach(function (chip) {
			var on = chip.getAttribute('data-filter') === value;
			chip.classList.toggle('is-active', on);
			chip.setAttribute('aria-pressed', on ? 'true' : 'false');
		});
		render();
	}

	chips.forEach(function (chip) {
		chip.addEventListener('click', function () { setFilter(chip.getAttribute('data-filter')); });
	});
	if (search) {
		search.addEventListener('input', function () {
			query = search.value.trim().toLowerCase();
			limit = PAGE;
			render();
		});
	}
	moreBtn.addEventListener('click', function () {
		limit += PAGE;
		render();
	});
	// sector cards and footer links pick a category before scrolling to the catalogue
	doc.addEventListener('click', function (e) {
		var link = e.target.closest('[data-filter-link]');
		if (!link) return;
		if (search) { search.value = ''; query = ''; }
		setFilter(link.getAttribute('data-filter-link'));
	}, true);

	grid.classList.add('is-ready');
	render();

	/* ---------- Product lightbox ---------- */
	var lb = $('#lightbox');
	if (!lb) return;
	var lbImg = $('#lbImg');
	var lbTitle = $('#lbTitle');
	var lbCat = $('#lbCat');
	var lbCount = $('#lbCount');
	var lbAsk = $('#lbAsk');
	var lbIndex = 0;
	var lastFocus = null;

	function lbShow(i) {
		if (!matches.length) return;
		lbIndex = (i + matches.length) % matches.length;
		var card = matches[lbIndex];
		var name = card.getAttribute('data-name');
		lb.classList.add('is-loading');
		lbImg.onload = lbImg.onerror = function () { lb.classList.remove('is-loading'); };
		lbImg.src = $('.product-media', card).getAttribute('href');
		lbImg.alt = name;
		lbTitle.textContent = name;
		lbCat.textContent = $('.product-cat', card).textContent;
		lbCount.textContent = (lbIndex + 1) + ' / ' + matches.length;
	}
	function lbOpen(card) {
		lastFocus = doc.activeElement;
		lb.hidden = false;
		doc.body.classList.add('lb-open');
		lbShow(Math.max(0, matches.indexOf(card)));
		requestAnimationFrame(function () { lb.classList.add('is-open'); });
		$('.lb-close', lb).focus();
	}
	function lbClose(restoreFocus) {
		lb.classList.remove('is-open');
		doc.body.classList.remove('lb-open');
		setTimeout(function () { lb.hidden = true; lbImg.removeAttribute('src'); }, 250);
		if (restoreFocus !== false && lastFocus) lastFocus.focus({ preventScroll: true });
	}

	grid.addEventListener('click', function (e) {
		var media = e.target.closest('.product-media');
		if (!media) return;
		e.preventDefault();
		lbOpen(media.closest('.product-card'));
	});
	$('.lb-close', lb).addEventListener('click', lbClose);
	$('.lb-prev', lb).addEventListener('click', function () { lbShow(lbIndex - 1); });
	$('.lb-next', lb).addEventListener('click', function () { lbShow(lbIndex + 1); });
	lb.addEventListener('click', function (e) {
		if (e.target === lb || e.target.classList.contains('lb-stage')) lbClose();
	});
	lbAsk.addEventListener('click', function () {
		var subject = $('#subjectField');
		if (subject) subject.value = 'Inquiry about: ' + lbTitle.textContent;
		lbClose(false);
		scrollToId('contact');
		var nameField = $('#main-contact-form input[name="name"]');
		if (nameField) setTimeout(function () { nameField.focus({ preventScroll: true }); }, 700);
	});

	doc.addEventListener('keydown', function (e) {
		if (lb.hidden) return;
		if (e.key === 'Escape') lbClose();
		else if (e.key === 'ArrowLeft') lbShow(lbIndex - 1);
		else if (e.key === 'ArrowRight') lbShow(lbIndex + 1);
		else if (e.key === 'Tab') {
			var focusables = $$('button', lb);
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
})();
