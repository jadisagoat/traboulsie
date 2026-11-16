/* Traboulsi Est. — option 5 (bilingual catalogue design: English page and Arabic page) */
(function () {
	'use strict';

	var doc = document;
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	var hasIO = 'IntersectionObserver' in window;

	function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
	function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }

	var root = doc.documentElement;
	var motion = root.classList.contains('motion') && !root.classList.contains('motion-off');
	window.O5_READY = true;
	var gsap = motion ? window.gsap : null;
	var ST = gsap ? window.ScrollTrigger : null;
	if (gsap && ST) gsap.registerPlugin(ST);
	var lenis = null;
	var finePointer = window.matchMedia && window.matchMedia('(hover: hover) and (pointer: fine)').matches;

	/* ---------- Header shadow, back to top, reading progress, parallax ---------- */
	var header = $('#siteHeader');
	var toTop = $('#toTop');
	var bar = $('#scrollBar');
	var heroSlides = $('.slides');
	var parallax = motion ? $$('[data-parallax]') : [];
	var ticking = false;
	function onScroll() {
		var y = window.pageYOffset || root.scrollTop;
		var max = root.scrollHeight - window.innerHeight;
		var h = window.innerHeight;
		if (header) header.classList.toggle('is-scrolled', y > 40);
		if (toTop) toTop.classList.toggle('is-visible', y > 800);
		if (bar) bar.style.transform = 'scaleX(' + (max > 0 ? Math.min(1, y / max) : 0) + ')';
		revealVisible();
		// the hero photos move slower than the page
		if (motion && !ST && heroSlides && y < h) heroSlides.style.transform = 'translate3d(0,' + (y * 0.35).toFixed(1) + 'px,0)';
		parallax.forEach(function (img) {
			var box = img.parentNode.getBoundingClientRect();
			if (box.bottom < 0 || box.top > h) return;
			var shift = (box.top + box.height / 2 - h / 2) / h;
			img.style.transform = 'translate3d(0,' + (shift * -90).toFixed(1) + 'px,0)';
		});
		ticking = false;
	}
	window.addEventListener('scroll', function () {
		if (!ticking) { ticking = true; requestAnimationFrame(onScroll); }
	}, { passive: true });
	window.addEventListener('resize', onScroll);
	onScroll();

	/* ---------- Intro loader (once per visit), then the hero title rises line by line ---------- */
	function ready() { requestAnimationFrame(function () { root.classList.add('is-ready'); }); }
	function heroWhenLoaded() {
		var firstSlide = $('.slide img');
		if (firstSlide && !firstSlide.complete) {
			firstSlide.addEventListener('load', ready);
			firstSlide.addEventListener('error', ready);
			setTimeout(ready, 1500);
		} else ready();
	}
	var loader = $('#loader');
	if (motion && loader && !root.classList.contains('skip-loader')) {
		var loaderBar = $('#loaderBar');
		var loaderNum = $('#loaderNum');
		var loaderYear = $('#loaderYear');
		var yearFrom = loaderYear ? parseInt(loaderYear.getAttribute('data-from'), 10) : 0;
		var yearTo = loaderYear ? parseInt(loaderYear.getAttribute('data-to'), 10) : 0;
		var started = Date.now();
		var finished = false;
		// the intro lasts at least 1.1 s and never more than 2.4 s (Facebook and the map can load slowly)
		var finish = function () {
			if (finished) return;
			finished = true;
			loaderBar.style.transform = 'scaleX(1)';
			loaderNum.textContent = '100';
			if (loaderYear) loaderYear.textContent = yearTo;
			loader.classList.add('is-done');
			try { sessionStorage.setItem('o5-intro', '1'); } catch (err) {}
			setTimeout(ready, 450);
			setTimeout(function () { loader.style.display = 'none'; }, 1300);
		};
		var whenLoaded = function () { setTimeout(finish, Math.max(0, 1900 - (Date.now() - started))); };
		if (doc.readyState === 'complete') whenLoaded(); else window.addEventListener('load', whenLoaded);
		setTimeout(finish, 2800);
		(function tick() {
			if (finished) return;
			var p = Math.min(0.96, (Date.now() - started) / 2400);
			loaderBar.style.transform = 'scaleX(' + p.toFixed(3) + ')';
			loaderNum.textContent = Math.round(p * 100);
			// the year counts from the founding year to today
			if (loaderYear) loaderYear.textContent = Math.round(yearFrom + (yearTo - yearFrom) * Math.min(1, (Date.now() - started) / 1700));
			requestAnimationFrame(tick);
		})();
	} else heroWhenLoaded();

	/* ---------- Split headings into words (or letters) that rise from behind a mask ---------- */
	function splitWords(el, chars) {
		if (el.getAttribute('data-split-done')) return;
		el.setAttribute('data-split-done', '1');
		var n = 0;
		(function walk(node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (child) {
				if (child.nodeType === 3) {
					var parts = chars ? child.textContent.split('') : child.textContent.split(/(\s+)/);
					var frag = doc.createDocumentFragment();
					parts.forEach(function (part) {
						if (!part) return;
						if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(part)); return; }
						var w = doc.createElement('span');
						w.className = 'w';
						var inner = doc.createElement('span');
						inner.textContent = part;
						inner.style.setProperty('--wd', (n++ * (chars ? 45 : 70)) + 'ms');
						w.appendChild(inner);
						frag.appendChild(w);
					});
					node.replaceChild(frag, child);
				} else if (child.nodeType === 1 && !child.classList.contains('w')) walk(child);
			});
		})(el);
	}
	if (motion) {
		$$('.section-head .h2, .products-head .h2, .row-title .h2, .about-body .h2, .band h2').forEach(function (h) {
			if (!h.hasAttribute('data-reveal')) h.setAttribute('data-reveal', 'split');
		});
		$$('[data-reveal="split"]').forEach(function (el) { splitWords(el, el.classList.contains('footer-giant')); });
	}

	/* ---------- Reveal on scroll ---------- */
	var revealer = null;
	// shows every hidden [data-reveal] element that is inside the window (safety net for the observer)
	function revealVisible() {
		if (!motion) return;
		var h = window.innerHeight;
		var pending = doc.querySelectorAll('[data-reveal]:not(.is-in), [data-feature]:not(.is-in)');
		for (var i = 0; i < pending.length; i++) {
			var box = pending[i].getBoundingClientRect();
			if (box.bottom > 0 && box.top < h * 0.92 && (box.width || box.height)) pending[i].classList.add('is-in');
		}
	}
	if (motion && hasIO) {
		revealer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				entry.target.classList.add('is-in');
				revealer.unobserve(entry.target);
			});
		}, { rootMargin: '0px 0px -10% 0px', threshold: 0.05 });
		$$('[data-reveal], [data-feature]').forEach(function (el) { revealer.observe(el); });
	} else {
		$$('[data-reveal], [data-feature]').forEach(function (el) { el.classList.add('is-in'); });
	}
	function reveal(el) {
		if (revealer) revealer.observe(el);
		else el.classList.add('is-in');
	}

	/* ---------- Counters count up when they come into view ---------- */
	function countUp(el) {
		var end = parseInt(el.getAttribute('data-count'), 10) || 0;
		var start = null;
		function step(t) {
			if (start === null) start = t;
			var p = Math.min(1, (t - start) / 2000);
			el.textContent = Math.round(end * (1 - Math.pow(1 - p, 4)));
			if (p < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	if (motion && hasIO) {
		var counter = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				countUp(entry.target);
				counter.unobserve(entry.target);
			});
		}, { threshold: 0.6 });
		$$('[data-count]').forEach(function (el) { el.textContent = '0'; counter.observe(el); });
	}

	/* ---------- Mobile menu ---------- */
	var nav = $('#nav');
	var menuToggle = $('#menuToggle');
	function setMenu(open) {
		if (!nav || !menuToggle) return;
		if (open && header) nav.style.setProperty('--nav-top', header.getBoundingClientRect().bottom + 'px');
		nav.classList.toggle('is-open', open);
		menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		menuToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		doc.body.classList.toggle('is-locked', open);
	}
	if (menuToggle) {
		menuToggle.addEventListener('click', function () { setMenu(menuToggle.getAttribute('aria-expanded') !== 'true'); });
		doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });
		window.addEventListener('resize', function () { if (window.innerWidth > 960) setMenu(false); });
	}

	/* ---------- In-page links ----------
	   The page sets <base href>, so "#about" would otherwise resolve to base_url#about
	   and reload the page when it is opened through /option4. */
	function scrollToId(id) {
		var target = doc.getElementById(id);
		if (!target) return false;
		if (lenis) lenis.scrollTo(target, { offset: -(header ? header.offsetHeight - 30 : 0), duration: 1.4 });
		else target.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'start' });
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
			link.blur();
		}
	});

	/* ---------- Active nav link ---------- */
	var navLinks = $$('.nav > ul > li > a[href^="#"]');
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

	/* ---------- Slider ---------- */
	var slider = $('.slider');
	var SLIDE_TIME = 6000;
	var slides = $$('.slide');
	var dots = $$('.slider-dots button');
	var slideNow = $('#slideNow');
	var current = 0;
	var timer = null;
	if (slider) slider.style.setProperty('--slide-time', SLIDE_TIME + 'ms');
	function showSlide(i) {
		if (!slides.length) return;
		current = (i + slides.length) % slides.length;
		slides.forEach(function (s, j) { s.classList.toggle('is-active', j === current); });
		dots.forEach(function (d, j) {
			d.classList.remove('is-active');
			d.classList.toggle('is-done', j < current);
		});
		if (dots[current]) {
			void dots[current].offsetWidth; // restart the line that fills up
			dots[current].classList.add('is-active');
		}
		if (slideNow) slideNow.textContent = (current < 9 ? '0' : '') + (current + 1);
	}
	function play() {
		clearInterval(timer);
		if (slides.length > 1 && !reduceMotion) timer = setInterval(function () { showSlide(current + 1); }, SLIDE_TIME);
	}
	dots.forEach(function (dot, i) { dot.addEventListener('click', function () { showSlide(i); play(); }); });
	var prev = $('.slider-arrow.prev');
	var next = $('.slider-arrow.next');
	if (prev) prev.addEventListener('click', function () { showSlide(current - 1); play(); });
	if (next) next.addEventListener('click', function () { showSlide(current + 1); play(); });
	if (slider && slides.length > 1) {
		var sx = null;
		slider.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
		slider.addEventListener('touchend', function (e) {
			if (sx === null) return;
			var dx = e.changedTouches[0].clientX - sx;
			if (Math.abs(dx) > 50) { showSlide(current + (dx < 0 ? 1 : -1)); play(); }
			sx = null;
		});
	}
	doc.addEventListener('visibilitychange', function () { if (doc.hidden) clearInterval(timer); else play(); });
	play();

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
			if (!aboutBtn.getAttribute('data-more')) aboutBtn.setAttribute('data-more', $('span', aboutBtn).textContent);
			$('span', aboutBtn).textContent = open ? (aboutBtn.getAttribute('data-less') || 'Read less') : aboutBtn.getAttribute('data-more');
		});
	}

	/* ---------- Ask about a product: fill the subject and open the form ---------- */
	function askAbout(name) {
		var subject = $('#subjectField');
		if (subject) subject.value = (document.documentElement.lang === 'ar' ? 'استفسار عن: ' : 'Inquiry about: ') + name;
		scrollToId('contact');
		var nameField = $('#main-contact-form input[name="name"]');
		if (nameField) setTimeout(function () { nameField.focus({ preventScroll: true }); }, 800);
	}

	/* ---------- Products: category, search, rows / grid, load more ---------- */
	var list = $('#productList');
	var matches = [];
	if (list) {
		var rows = $$('.p-row', list);
		var chips = $$('.chip[data-filter]');
		var tiles = $$('.cat-tile[data-filter-link]');
		var views = $$('[data-view]');
		var search = $('#productSearch');
		var moreBtn = $('#loadMore');
		var moreWrap = $('#loadMoreWrap');
		var shownEl = $('#shownCount');
		var totalEl = $('#totalCount');
		var emptyEl = $('#productsEmpty');
		var view = 'rows';
		var PAGE = { rows: 6, grid: 16 };
		var filter = '*';
		var query = '';
		var limit = PAGE.rows;

		var render = function (append) {
			var before = append ? Math.min(limit - PAGE[view], matches.length) : 0;
			var cols = view === 'grid' ? (window.innerWidth > 1100 ? 4 : window.innerWidth > 767 ? 3 : 2) : 1;
			matches = rows.filter(function (row) {
				return (filter === '*' || row.getAttribute('data-cat') === filter) &&
					(!query || row.getAttribute('data-search').indexOf(query) !== -1);
			});
			rows.forEach(function (row) { row.classList.remove('is-shown'); });
			matches.forEach(function (row, i) {
				if (i >= limit) return;
				// image left / right alternates on what is visible, like the reference
				row.classList.toggle('is-flip', i % 2 === 1);
				var wasIn = append && i < before && row.classList.contains('is-in');
				row.classList.add('is-shown');
				if (!wasIn) {
					row.classList.remove('is-in');
					row.style.setProperty('--g', ((i - before) % cols) * 90 + 'ms');
					if (revealer) revealer.unobserve(row);
					reveal(row);
				}
			});
			var shown = Math.min(limit, matches.length);
			shownEl.textContent = shown;
			totalEl.textContent = matches.length;
			moreBtn.hidden = shown >= matches.length;
			refreshScroll();
			moreWrap.hidden = matches.length === 0;
			emptyEl.hidden = matches.length > 0;
		};

		var setFilter = function (value) {
			filter = value;
			limit = PAGE[view];
			chips.forEach(function (chip) {
				var on = chip.getAttribute('data-filter') === value;
				chip.classList.toggle('is-active', on);
				chip.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			tiles.forEach(function (tile) { tile.classList.toggle('is-active', value !== '*' && tile.getAttribute('data-filter-link') === value); });
			render(false);
		};

		chips.forEach(function (chip) {
			chip.addEventListener('click', function () { setFilter(chip.getAttribute('data-filter')); });
		});
		views.forEach(function (btn) {
			btn.addEventListener('click', function () {
				view = btn.getAttribute('data-view');
				views.forEach(function (b) {
					var on = b === btn;
					b.classList.toggle('is-active', on);
					b.setAttribute('aria-pressed', on ? 'true' : 'false');
				});
				list.classList.toggle('is-rows', view === 'rows');
				list.classList.toggle('is-grid', view === 'grid');
				limit = Math.max(PAGE[view], Math.ceil(Math.min(limit, matches.length) / PAGE[view]) * PAGE[view]);
				render(false);
				try { localStorage.setItem('o4-view', view); } catch (err) {}
			});
		});
		if (search) {
			search.addEventListener('input', function () {
				query = search.value.trim().toLowerCase();
				limit = PAGE[view];
				render(false);
			});
		}
		moreBtn.addEventListener('click', function () {
			limit += PAGE[view];
			render(true);
		});
		// category tiles, the Products menu and footer links pick a category before scrolling
		doc.addEventListener('click', function (e) {
			var link = e.target.closest('[data-filter-link]');
			if (!link) return;
			if (search) { search.value = ''; query = ''; }
			setFilter(link.getAttribute('data-filter-link'));
		}, true);

		// the search icon in the header jumps to the product search
		var searchToggle = $('#searchToggle');
		if (searchToggle && search) {
			searchToggle.addEventListener('click', function () {
				setMenu(false);
				scrollToId('products');
				setTimeout(function () { search.focus({ preventScroll: true }); }, reduceMotion ? 0 : 600);
			});
		}

		list.addEventListener('click', function (e) {
			var row = e.target.closest('.p-row');
			if (!row) return;
			if (e.target.closest('[data-ask]')) {
				askAbout($('.p-name', row).textContent);
				return;
			}
			if (e.target.closest('[data-more]') || e.target.closest('.p-img')) {
				e.preventDefault();
				lbOpen(productItems(), matches.indexOf(row), false);
			}
		});

		try {
			var saved = localStorage.getItem('o4-view');
			if (saved === 'grid') { var g = $('[data-view="grid"]'); if (g) g.click(); }
		} catch (err) {}
		render(false);
	}

	function productItems() {
		return matches.map(function (row) {
			return {
				src: $('.p-img', row).getAttribute('href'),
				title: $('.p-name', row).textContent,
				cat: $('.p-cat', row).textContent,
				text: $('.p-desc', row).textContent
			};
		});
	}

	/* ---------- Showroom arrows ---------- */
	$$('[data-scroll]').forEach(function (btn) {
		btn.addEventListener('click', function () {
			var track = doc.getElementById(btn.getAttribute('data-track') || 'showTrack');
			if (!track) return;
			var first = track.firstElementChild;
			var gap = parseFloat(getComputedStyle(track).columnGap || getComputedStyle(track).gap) || 24;
			var one = first ? first.getBoundingClientRect().width + gap : track.clientWidth;
			var step = one * (btn.getAttribute('data-scroll') === '1' ? 1 : -1);
			var max = track.scrollWidth - track.clientWidth;
			var to = track.scrollLeft + step;
			if (to > max + 5) to = 0;
			else if (to < -5) to = max;
			track.scrollTo({ left: to, behavior: reduceMotion ? 'auto' : 'smooth' });
		});
	});

	/* ---------- Footer columns open and close on phones ---------- */
	$$('[data-collapse]').forEach(function (col) {
		var btn = $('.footer-title', col);
		btn.addEventListener('click', function () {
			if (window.innerWidth > 767) return;
			var open = !col.classList.contains('is-open');
			col.classList.toggle('is-open', open);
			btn.setAttribute('aria-expanded', open ? 'true' : 'false');
		});
	});

	/* ---------- Lightbox (products and showroom photos) ---------- */
	var lb = $('#lightbox');
	var lbImg = $('#lbImg');
	var lbTitle = $('#lbTitle');
	var lbCat = $('#lbCat');
	var lbDesc = $('#lbDesc');
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
		lbDesc.textContent = item.text || '';
		lbCount.textContent = (lbIndex + 1) + ' / ' + items.length;
		var many = items.length > 1;
		$('.lb-prev', lb).hidden = !many;
		$('.lb-next', lb).hidden = !many;
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
		setTimeout(function () { lb.hidden = true; lbImg.removeAttribute('src'); }, 250);
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
		lb.addEventListener('click', function (e) { if (e.target === lb) lbClose(); });
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

	/* =====================================================================
	   Motion layer (GSAP + ScrollTrigger + Lenis). Skipped when the visitor
	   asked for less motion or the libraries did not load: the page still works.
	   ===================================================================== */
	var refreshTimer = null;
	function refreshScroll() {
		if (!ST) return;
		clearTimeout(refreshTimer);
		refreshTimer = setTimeout(function () { ST.refresh(); }, 120);
	}

	/* ---------- Smooth scrolling ---------- */
	if (motion && window.Lenis) {
		lenis = new window.Lenis({ lerp: 0.09, wheelMultiplier: 1 });
		if (gsap && ST) {
			lenis.on('scroll', ST.update);
			gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
			gsap.ticker.lagSmoothing(0);
		} else {
			(function raf(time) { lenis.raf(time); requestAnimationFrame(raf); })(0);
		}
		// menus and the lightbox scroll on their own
		$$('#lightbox, #nav, .video-track, .show-track').forEach(function (el) { el.setAttribute('data-lenis-prevent', ''); });
		// stop the page while the menu or the lightbox is open
		new MutationObserver(function () {
			if (doc.body.classList.contains('is-locked')) lenis.stop(); else lenis.start();
		}).observe(doc.body, { attributes: true, attributeFilter: ['class'] });
	}

	/* ---------- Cursor: a ring that follows the mouse, "View" / "Play" over photos and videos ---------- */
	var cursor = $('#cursor');
	var cursorDot = $('#cursorDot');
	var cursorLabel = $('#cursorLabel');
	if (motion && finePointer && cursor) {
		root.classList.add('has-cursor');
		var mx = -100, my = -100, cx = -100, cy = -100;
		doc.addEventListener('mousemove', function (e) {
			mx = e.clientX; my = e.clientY;
			cursorDot.style.transform = 'translate3d(' + mx + 'px,' + my + 'px,0)';
			cursor.classList.remove('is-hidden'); cursorDot.classList.remove('is-hidden');
		}, { passive: true });
		doc.addEventListener('mouseleave', function () { cursor.classList.add('is-hidden'); cursorDot.classList.add('is-hidden'); });
		(function follow() {
			cx += (mx - cx) * 0.18; cy += (my - cy) * 0.18;
			cursor.style.transform = 'translate3d(' + cx.toFixed(1) + 'px,' + cy.toFixed(1) + 'px,0)';
			requestAnimationFrame(follow);
		})();
		doc.addEventListener('mouseover', function (e) {
			var labelled = e.target.closest('[data-cursor]');
			var link = e.target.closest('a, button, input, textarea, label');
			cursor.classList.toggle('is-label', !!labelled);
			cursor.classList.toggle('is-link', !labelled && !!link);
			cursorLabel.textContent = labelled ? labelled.getAttribute('data-cursor') : '';
		});
	}

	/* ---------- Magnetic buttons ---------- */
	if (motion && finePointer) {
		$$('[data-magnetic], .arrow-btn, .slider-arrow, .to-top, .footer-social a').forEach(function (btn) {
			btn.addEventListener('mousemove', function (e) {
				var r = btn.getBoundingClientRect();
				var x = (e.clientX - r.left - r.width / 2) * 0.3;
				var y = (e.clientY - r.top - r.height / 2) * 0.4;
				btn.style.transition = 'translate .2s ease-out';
				btn.style.translate = x.toFixed(1) + 'px ' + y.toFixed(1) + 'px';
			});
			btn.addEventListener('mouseleave', function () {
				btn.style.transition = 'translate .6s cubic-bezier(.2,.7,.2,1)';
				btn.style.translate = '0px 0px';
			});
		});

		/* ---------- Category tiles and feature cards tilt in 3D under the mouse ---------- */
		$$('[data-tilt], [data-feature]').forEach(function (tile) {
			tile.addEventListener('mousemove', function (e) {
				if (!tile.classList.contains('is-in')) return;
				var r = tile.getBoundingClientRect();
				var px = (e.clientX - r.left) / r.width;
				var py = (e.clientY - r.top) / r.height;
				tile.style.transition = 'transform .15s ease-out, color .45s';
				tile.style.transform = 'perspective(800px) rotateX(' + ((0.5 - py) * 16).toFixed(2) + 'deg) rotateY(' + ((px - 0.5) * 16).toFixed(2) + 'deg) translateZ(10px)';
				tile.style.setProperty('--mx', (px * 100).toFixed(1) + '%');
				tile.style.setProperty('--my', (py * 100).toFixed(1) + '%');
			});
			tile.addEventListener('mouseleave', function () {
				tile.style.transition = 'transform .7s cubic-bezier(.2,.7,.2,1), color .45s';
				tile.style.transform = '';
			});
		});
	}

	/* ---------- Scroll-driven effects ---------- */
	if (gsap && ST) {
		// hero: the photos sink slower, the title floats up and fades
		var heroSlidesEl = $('.slides');
		var caption = $('.slider-caption');
		if (slider && heroSlidesEl) {
			gsap.to(heroSlidesEl, { yPercent: 22, scale: 1.08, ease: 'none', scrollTrigger: { trigger: slider, start: 'top top', end: 'bottom top', scrub: true } });
		}
		if (slider && caption) {
			gsap.to(caption, { y: -120, opacity: 0, ease: 'none', scrollTrigger: { trigger: slider, start: 'top top', end: '70% top', scrub: true } });
		}

		// quote: the words light up one after the other as the page scrolls
		// the words of the services light up in the brand gradient
		var keyWords = /^(refrigerat|showcase|grill|stainless|restaurant|hotel|supermarket|butcher|baker|pastr|hospital|caf|kitchen|البرادات|الشوايات|الستانلس|المطاعم|الفنادق|السوبرماركت|الملاحم|الأفران|المستشفيات|ثلاجات)/i;
		$$('[data-words]').forEach(function (el) {
			el.innerHTML = el.textContent.split(/(\s+)/).map(function (part) {
				if (/^\s+$/.test(part)) return part;
				var bare = part.replace(/^[^\w؀-ۿ]+/, '').replace(/^و(?=ال)/, '');
				return '<span class="hw' + (keyWords.test(bare) ? ' is-key' : '') + '">' + part.replace(/&/g, '&amp;').replace(/</g, '&lt;') + '</span>';
			}).join('');
			gsap.fromTo($$('.hw', el), { opacity: 0.14 }, { opacity: 1, stagger: 0.08, ease: 'none', scrollTrigger: { trigger: el, start: 'top 82%', end: 'bottom 50%', scrub: true } });
		});

		// the giant year slides sideways behind the quote
		$$('[data-drift]').forEach(function (el) {
			gsap.fromTo(el, { xPercent: -62 }, { xPercent: -38, ease: 'none', scrollTrigger: { trigger: el.parentNode, start: 'top bottom', end: 'bottom top', scrub: true } });
		});

		// product rows: the giant model number floats against the scroll
		$$('.p-ghost').forEach(function (el) {
			gsap.fromTo(el, { y: 110 }, { y: -110, ease: 'none', scrollTrigger: { trigger: el.closest('.p-row'), start: 'top bottom', end: 'bottom top', scrub: true } });
		});

		// client names lean with the speed of the scroll
		var skew = $('#marqueeSkew');
		if (skew) {
			var setSkew = gsap.quickTo(skew, 'skewX', { duration: 0.5, ease: 'power3' });
			ST.create({
				trigger: skew, start: 'top bottom', end: 'bottom top',
				onUpdate: function (self) { setSkew(Math.max(-14, Math.min(14, self.getVelocity() / -260))); }
			});
		}

		// showroom: pinned while the photos scroll sideways (large screens)
		var mm = gsap.matchMedia();
		mm.add('(min-width: 961px)', function () {
			var section = $('[data-pin-scroll]');
			var track = section ? $('.show-track', section) : null;
			if (!section || !track || track.children.length < 4) return;
			section.classList.add('is-pinned');
			var distance = function () { return Math.max(0, track.scrollWidth - track.parentNode.clientWidth); };
			var tween = gsap.to(track, {
				x: function () { return -distance(); }, ease: 'none',
				scrollTrigger: {
					trigger: section, pin: true, scrub: 1, invalidateOnRefresh: true,
					start: function () { return 'top ' + (header ? header.offsetHeight : 0) + 'px'; },
					end: function () { return '+=' + distance(); }
				}
			});
			return function () { tween.kill(); gsap.set(track, { clearProps: 'transform' }); section.classList.remove('is-pinned'); };
		});

		window.addEventListener('load', refreshScroll);
	}

	/* ---------- About: the photo wipes open, a frame slides behind it, the year counts up ---------- */
	var aboutSec = $('#about');
	if (gsap && ST && aboutSec) {
		var aBody = $('.about-body', aboutSec);
		var aFig = $('.about-img', aboutSec);
		var aPhoto = aFig && $('.about-photo', aFig);
		var aImg = aFig && $('img', aFig);
		var aSub = aFig && $('.about-sub', aFig);
		var aBadge = aFig && $('figcaption', aFig);
		var aYear = aBadge && $('strong', aBadge);
		[aBody, aFig].forEach(function (el) { if (el) el.classList.add('gsap-driven'); });
		var atl = gsap.timeline({ scrollTrigger: { trigger: aboutSec, start: 'top 72%', once: true } });
		if (aPhoto) {
			atl.fromTo(aPhoto, { clipPath: 'inset(0 100% 0 0 round 14px)' }, { clipPath: 'inset(0 0% 0 0 round 14px)', duration: 1.3, ease: 'power3.inOut' }, 0)
				.fromTo(aImg, { scale: 1.3 }, { scale: 1, duration: 1.9, ease: 'power3.out' }, 0);
		}
		if (aSub) atl.fromTo(aSub, { x: 60, y: 60, opacity: 0, rotate: 4 }, { x: 0, y: 0, opacity: 1, rotate: 0, duration: 1.1, ease: 'power3.out' }, 0.7);
		if (aBadge) atl.fromTo(aBadge, { y: -30, opacity: 0, scale: 0.85 }, { y: 0, opacity: 1, scale: 1, duration: 0.8, ease: 'back.out(1.7)' }, 0.9);
		if (aYear) {
			var yEnd = parseInt(aYear.textContent, 10);
			if (yEnd) {
				var yObj = { v: yEnd - 60 };
				atl.to(yObj, { v: yEnd, duration: 1.6, ease: 'power2.out', onUpdate: function () { aYear.textContent = Math.round(yObj.v); } }, 0.9);
			}
		}
		if (aBody) {
			var lines = Array.prototype.filter.call(aBody.children, function (el) { return el.getAttribute('data-reveal') !== 'split'; });
			atl.fromTo(lines, { y: 36, opacity: 0 }, { y: 0, opacity: 1, duration: 0.9, stagger: 0.12, ease: 'power3.out' }, 0.15);
		}
		// the photos drift at different speeds while the page scrolls past (depth)
		if (aImg) gsap.fromTo(aImg, { yPercent: -5 }, { yPercent: 5, ease: 'none', scrollTrigger: { trigger: aboutSec, start: 'top bottom', end: 'bottom top', scrub: true } });
		if (aSub) gsap.fromTo(aSub, { y: 40 }, { y: -40, ease: 'none', scrollTrigger: { trigger: aboutSec, start: 'top bottom', end: 'bottom top', scrub: true } });
	}

	/* ---------- Footer name: the light sweeps across it without stopping ----------
	   Driven by GSAP's ticker: a CSS animation on this element gets paused by the smooth scrolling. */
	var shineEls = $$('.giant-text, .giant-glow');
	if (gsap && shineEls.length) {
		root.classList.add('js-shine');
		// the light band is 20% of the gradient wide: this range is just enough to clear both edges,
		// so the next pass starts right after the previous one leaves
		var pos = { x: 100 };
		gsap.set(shineEls, { backgroundPosition: '100% 0' });
		gsap.to(pos, {
			x: -20, duration: 4, ease: 'none', repeat: -1,
			onUpdate: function () {
				var v = pos.x.toFixed(2) + '% 0';
				for (var i = 0; i < shineEls.length; i++) shineEls[i].style.backgroundPosition = v;
			}
		});
	}

	/* ---------- Scroll to the form after it was sent ---------- */
	if ($('#main-contact-form .alert')) scrollToId('contact');
})();


/* ---------- Welding sparks (intro and hero), the hero follows the mouse ---------- */
(function () {
	'use strict';
	var root = document.documentElement;
	if (!root.classList.contains('motion') || root.classList.contains('motion-off')) return;
	var small = window.matchMedia('(max-width: 760px)').matches;

	// a shower of sparks from one point: bursts of glowing streaks that fall with gravity
	function sparks(canvas, opts) {
		if (!canvas || !canvas.getContext) return null;
		var ctx = canvas.getContext('2d');
		var dpr = Math.min(2, window.devicePixelRatio || 1);
		var w = 0, h = 0, parts = [], running = false, nextBurst = 0, flash = 0;
		function size() {
			var r = canvas.getBoundingClientRect();
			w = r.width; h = r.height;
			canvas.width = Math.round(w * dpr); canvas.height = Math.round(h * dpr);
			ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
		}
		function burst() {
			var o = opts.origin(w, h);
			var n = Math.round((small ? 0.55 : 1) * (opts.min + Math.random() * (opts.max - opts.min)));
			for (var i = 0; i < n; i++) {
				var a = opts.angle + (Math.random() - 0.5) * opts.spread;
				var v = opts.speed * (0.35 + Math.random() * 0.9);
				parts.push({ x: o.x, y: o.y, px: o.x, py: o.y, vx: Math.cos(a) * v, vy: Math.sin(a) * v, life: 0, max: 35 + Math.random() * 55, hue: 18 + Math.random() * 28 });
			}
			flash = 1;
		}
		function frame(t) {
			if (!running) return;
			if (t > nextBurst) { burst(); nextBurst = t + opts.every * (0.45 + Math.random()); }
			ctx.clearRect(0, 0, w, h);
			ctx.globalCompositeOperation = 'lighter';
			if (flash > 0.02) {
				var o = opts.origin(w, h);
				var g = ctx.createRadialGradient(o.x, o.y, 0, o.x, o.y, 90);
				g.addColorStop(0, 'rgba(255,190,120,' + (0.55 * flash) + ')');
				g.addColorStop(1, 'rgba(255,90,40,0)');
				ctx.fillStyle = g; ctx.beginPath(); ctx.arc(o.x, o.y, 90, 0, 6.283); ctx.fill();
				flash *= 0.9;
			}
			ctx.lineCap = 'round';
			for (var i = parts.length - 1; i >= 0; i--) {
				var p = parts[i];
				p.px = p.x; p.py = p.y;
				p.vy += 0.16; p.vx *= 0.985;
				p.x += p.vx; p.y += p.vy; p.life++;
				var k = 1 - p.life / p.max;
				if (k <= 0 || p.y > h + 20) { parts.splice(i, 1); continue; }
				ctx.strokeStyle = 'hsla(' + p.hue + ',100%,' + (55 + k * 30) + '%,' + k + ')';
				ctx.lineWidth = 0.8 + k * 2.4;
				ctx.beginPath(); ctx.moveTo(p.px - p.vx * 2.2, p.py - p.vy * 2.2); ctx.lineTo(p.x, p.y); ctx.stroke();
			}
			requestAnimationFrame(frame);
		}
		size();
		window.addEventListener('resize', size);
		return {
			start: function () { if (!running) { running = true; requestAnimationFrame(frame); } },
			stop: function () { running = false; }
		};
	}

	// intro: sparks rise and fall around the year
	var intro = sparks(document.getElementById('loaderSparks'), {
		origin: function (w, h) { return { x: w / 2 + (Math.random() - 0.5) * 160, y: h / 2 + 70 }; },
		angle: -Math.PI / 2, spread: 1.9, speed: 9, min: 22, max: 46, every: 240
	});
	var loader = document.getElementById('loader');
	if (intro && loader && !root.classList.contains('skip-loader')) {
		intro.start();
		setTimeout(function () { intro.stop(); }, 3400);
	}

	// hero: a welder at the bottom corner, sparks thrown up towards the middle of the photo
	var rtl = root.getAttribute('dir') === 'rtl';
	var hero = document.getElementById('home');
	var heroSparks = sparks(document.getElementById('heroSparks'), {
		origin: function (w, h) { return { x: rtl ? -12 : w + 12, y: h + 10 }; },
		angle: rtl ? -Math.PI * 0.3 : -Math.PI * 0.7, spread: 1.0, speed: small ? 10 : 15, min: 30, max: 60, every: 650
	});
	if (heroSparks && hero) {
		if ('IntersectionObserver' in window) {
			new IntersectionObserver(function (entries) {
				entries[0].isIntersecting && !document.hidden ? heroSparks.start() : heroSparks.stop();
			}).observe(hero);
		} else heroSparks.start();
		document.addEventListener('visibilitychange', function () { document.hidden ? heroSparks.stop() : heroSparks.start(); });
	}

	// the hero texts, stamp and cards move a little with the mouse
	if (hero && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
		hero.addEventListener('mousemove', function (e) {
			var r = hero.getBoundingClientRect();
			hero.style.setProperty('--mx', ((e.clientX - r.left) / r.width - 0.5).toFixed(3));
			hero.style.setProperty('--my', ((e.clientY - r.top) / r.height - 0.5).toFixed(3));
		});
		hero.addEventListener('mouseleave', function () { hero.style.setProperty('--mx', 0); hero.style.setProperty('--my', 0); });
	}
})();
