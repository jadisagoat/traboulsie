/* Traboulsi Est. — option 3 (motion design)
   GSAP + ScrollTrigger + Lenis (js_site/vendor). Every effect has a plain fallback:
   without them (or with reduced motion) the page is a normal, fully usable page. */
(function () {
	'use strict';

	var doc = document;
	var html = doc.documentElement;
	var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
	var fine = window.matchMedia && matchMedia('(hover: hover) and (pointer: fine)').matches;
	var gsap = window.gsap;
	var ST = window.ScrollTrigger;
	var motion = !!(gsap && ST) && !reduce;
	var hasIO = 'IntersectionObserver' in window;
	window.O3_READY = true;

	function $(sel, ctx) { return (ctx || doc).querySelector(sel); }
	function $$(sel, ctx) { return Array.prototype.slice.call((ctx || doc).querySelectorAll(sel)); }
	function pad(n) { return (n < 10 ? '0' : '') + n; }

	if (motion) gsap.registerPlugin(ST);

	/* ---------- Text splitting ---------- */
	// letters, grouped by word so lines still break between words
	function splitChars(el) {
		var chars = [];
		(function walk(node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (child) {
				if (child.nodeType === 3) {
					var frag = doc.createDocumentFragment();
					child.textContent.split(/(\s+)/).forEach(function (part) {
						if (!part) return;
						if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(' ')); return; }
						var word = doc.createElement('span');
						word.className = 'word';
						part.split('').forEach(function (c) {
							var ch = doc.createElement('span');
							ch.className = 'char';
							ch.textContent = c;
							word.appendChild(ch);
							chars.push(ch);
						});
						frag.appendChild(word);
					});
					node.replaceChild(frag, child);
				} else if (child.nodeType === 1) {
					walk(child);
				}
			});
		})(el);
		el.classList.add('is-split');
		return chars;
	}
	// words, each inside a clipping box
	function splitWords(el) {
		var words = [];
		(function walk(node) {
			Array.prototype.slice.call(node.childNodes).forEach(function (child) {
				if (child.nodeType === 3) {
					var frag = doc.createDocumentFragment();
					child.textContent.split(/(\s+)/).forEach(function (part) {
						if (!part) return;
						if (/^\s+$/.test(part)) { frag.appendChild(doc.createTextNode(' ')); return; }
						var w = doc.createElement('span');
						var inner = doc.createElement('span');
						w.className = 'w';
						inner.className = 'w-in';
						inner.textContent = part;
						w.appendChild(inner);
						frag.appendChild(w);
						words.push(inner);
					});
					node.replaceChild(frag, child);
				} else if (child.nodeType === 1 && child.tagName !== 'BR') {
					walk(child);
				}
			});
		})(el);
		return words;
	}

	/* ---------- Rolling hover text: duplicate the label ---------- */
	$$('.roll').forEach(function (el) {
		var text = el.textContent;
		el.textContent = '';
		var inner = doc.createElement('span');
		inner.className = 'roll-in';
		var a = doc.createElement('span');
		var b = doc.createElement('span');
		a.textContent = text;
		b.textContent = text;
		b.setAttribute('aria-hidden', 'true');
		inner.appendChild(a);
		inner.appendChild(b);
		el.appendChild(inner);
	});

	/* ---------- Smooth scroll ---------- */
	var lenis = null;
	if (motion && window.Lenis) {
		lenis = new window.Lenis({ duration: 1.15, easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); } });
		lenis.on('scroll', ST.update);
		gsap.ticker.add(function (time) { lenis.raf(time * 1000); });
		gsap.ticker.lagSmoothing(0);
		html.classList.add('o3-smooth');
	}
	function stopScroll() { if (lenis) lenis.stop(); doc.body.classList.add('is-locked'); }
	function startScroll() { if (lenis) lenis.start(); doc.body.classList.remove('is-locked'); }

	var refreshTimer = null;
	function refreshSoon(delay) {
		if (!motion) return;
		clearTimeout(refreshTimer);
		refreshTimer = setTimeout(function () { ST.refresh(); }, delay || 150);
	}

	/* ---------- In-page links ----------
	   The page sets <base href>, so "#about" would otherwise resolve to base_url#about
	   and reload the page when it is opened through /option3. */
	function scrollToId(id, instant) {
		var target = doc.getElementById(id);
		if (!target) return false;
		if (lenis && !instant) lenis.scrollTo(target, { duration: 1.6 });
		else target.scrollIntoView({ behavior: (reduce || instant) ? 'auto' : 'smooth', block: 'start' });
		if (history.replaceState) history.replaceState(null, '', location.pathname + location.search + '#' + id);
		return true;
	}

	/* ---------- Header, back to top, marquee speed ---------- */
	var header = $('#siteHeader');
	var hero = $('#home');
	var toTop = $('#toTop');
	var ring = $('#topRing');
	var lastY = window.pageYOffset;
	var velocity = 0;
	var menuOpen = false;

	function onScroll() {
		var y = window.pageYOffset || html.scrollTop;
		var max = html.scrollHeight - window.innerHeight;
		var progress = max > 0 ? Math.min(1, y / max) : 0;
		var heroH = hero ? hero.offsetHeight : 600;
		var dy = y - lastY;
		velocity = dy;
		if (header && !menuOpen) {
			header.classList.toggle('is-scrolled', y > 40);
			header.classList.toggle('is-light', y > heroH - 90);
		}
		if (toTop) toTop.classList.toggle('is-visible', y > 800);
		if (ring) ring.style.strokeDashoffset = (138.2 * (1 - progress)).toFixed(1);
		lastY = y;
	}
	// the window scroll event fires with and without smooth scrolling
	var ticking = false;
	window.addEventListener('scroll', function () {
		if (!ticking) { ticking = true; requestAnimationFrame(function () { onScroll(); ticking = false; }); }
	}, { passive: true });
	onScroll();

	// marquees speed up with the scroll and follow its direction
	if (!reduce) {
		var marquees = $$('[data-marquee]').map(function (el) {
			// same calm speed whatever the length: px per second (client names slower)
			var pps = el.closest('.names') ? 38 : 60;
			el.style.animationDuration = Math.max(20, el.scrollWidth / 2 / pps).toFixed(1) + 's';
			var anim = el.getAnimations ? el.getAnimations()[0] : null;
			return anim ? { anim: anim, rate: 1 } : null;
		}).filter(Boolean);
		if (marquees.length) {
			var dir = 1;
			(function loop() {
				if (Math.abs(velocity) > .5) dir = velocity > 0 ? 1 : -1;
				var target = dir * (1 + Math.min(Math.abs(velocity) * .05, 1.5));
				marquees.forEach(function (m) {
					m.rate += (target - m.rate) * .08;
					m.anim.playbackRate = m.rate;
				});
				velocity *= .9;
				requestAnimationFrame(loop);
			})();
		}
	}

	/* ---------- Menu ---------- */
	var menu = $('#menu');
	var menuToggle = $('#menuToggle');
	function setMenu(open) {
		if (!menu || !menuToggle || open === menuOpen) return;
		menuOpen = open;
		if (open) {
			menu.hidden = false;
			if (header) header.classList.remove('is-hidden');
			requestAnimationFrame(function () { requestAnimationFrame(function () { menu.classList.add('is-open'); }); });
			stopScroll();
		} else {
			menu.classList.remove('is-open');
			setTimeout(function () { if (!menuOpen) menu.hidden = true; }, 900);
			startScroll();
		}
		doc.body.classList.toggle('menu-open', open);
		menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		menuToggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
	}
	if (menuToggle) {
		menuToggle.addEventListener('click', function () { setMenu(!menuOpen); });
		doc.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });
	}

	doc.addEventListener('click', function (e) {
		var link = e.target.closest('a[href^="#"]');
		if (!link) return;
		var id = link.getAttribute('href').slice(1);
		if (!id || !doc.getElementById(id)) return;
		e.preventDefault();
		var wasOpen = menuOpen;
		setMenu(false);
		// let the menu start closing before the page moves
		setTimeout(function () { scrollToId(id); }, wasOpen ? 350 : 0);
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
		if (heroNow) heroNow.textContent = pad(current + 1);
	}
	function play() {
		clearInterval(timer);
		if (slides.length > 1 && !reduce) timer = setInterval(function () { showSlide(current + 1); }, 7000);
	}
	dots.forEach(function (dot, i) {
		dot.addEventListener('click', function () { showSlide(i); play(); });
	});
	doc.addEventListener('visibilitychange', function () {
		if (doc.hidden) clearInterval(timer); else play();
	});

	/* ---------- Reveal on scroll (no GSAP needed) ---------- */
	var reveals = $$('[data-reveal]');
	if (hasIO && !reduce) {
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
			var p = Math.min(1, (t - start) / 2000);
			el.textContent = Math.round(end * (1 - Math.pow(1 - p, 4)));
			if (p < 1) requestAnimationFrame(step);
		}
		requestAnimationFrame(step);
	}
	function watchCounters() {
		if (!hasIO || reduce) return;
		var counter = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (!entry.isIntersecting) return;
				countUp(entry.target);
				counter.unobserve(entry.target);
			});
		}, { threshold: .6 });
		$$('[data-count]').forEach(function (el) { el.textContent = '0'; counter.observe(el); });
	}

	/* ==========================================================================
	   GSAP scenes
	   ========================================================================== */
	var heroChars = [];
	var heroIntro = null;

	if (motion) {
		// hero title letters wait behind the intro screen
		$$('.hero-title [data-split]').forEach(function (el) { heroChars = heroChars.concat(splitChars(el)); });
		gsap.set(heroChars, { yPercent: 115, rotate: 8 });
		gsap.set('.hero-eyebrow, .hero-lead, .hero-ar, .hero-actions > *, .hero-foot-inner > *', { y: 40, opacity: 0 });

		heroIntro = function () {
			var tl = gsap.timeline({ defaults: { ease: 'expo.out' } });
			tl.fromTo('.hero-media', { scale: 1.3 }, { scale: 1, duration: 2.4, ease: 'power3.out' }, 0)
				.to('.hero-eyebrow', { y: 0, opacity: 1, duration: 1.2 }, .15)
				.to(heroChars, { yPercent: 0, rotate: 0, duration: 1.3, stagger: .018 }, .2)
				.to('.hero-lead, .hero-ar', { y: 0, opacity: 1, duration: 1.2, stagger: .08 }, .7)
				.to('.hero-actions > *', { y: 0, opacity: 1, duration: 1.2, stagger: .08 }, .8)
				.to('.hero-foot-inner > *', { y: 0, opacity: 1, duration: 1.2, stagger: .1 }, 1);
			return tl;
		};

		// hero: the photo shrinks into a rounded card and the text drifts up
		var frame = $('#heroFrame');
		if (frame) {
			gsap.set(frame, { clipPath: 'inset(0% 0% 0% 0% round 0px)' });
			gsap.to(frame, {
				clipPath: 'inset(0% 2.5% 7% 2.5% round 36px)', ease: 'none',
				scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true }
			});
			gsap.to('.hero-media', { yPercent: 18, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
			gsap.to('.hero-content', { y: -120, opacity: 0, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: '60% top', scrub: true } });
			gsap.to('.hero-foot', { y: -80, opacity: 0, ease: 'none', scrollTrigger: { trigger: hero, start: 'top top', end: '40% top', scrub: true } });
		}

		// big statements light up word by word as they scroll by
		$$('[data-words="scrub"]').forEach(function (el) {
			var words = splitWords(el);
			gsap.fromTo(words, { opacity: .12 }, {
				opacity: 1, stagger: .1, ease: 'none',
				scrollTrigger: { trigger: el, start: 'top 85%', end: 'bottom 50%', scrub: true }
			});
		});
		// titles rise word by word
		$$('[data-words]:not([data-words="scrub"])').forEach(function (el) {
			var words = splitWords(el);
			gsap.from(words, {
				yPercent: 110, duration: 1.2, ease: 'expo.out', stagger: .06,
				scrollTrigger: { trigger: el, start: 'top 88%' }
			});
		});
		// eyebrows slide in
		$$('.section .eyebrow, .hs-intro .eyebrow').forEach(function (el) {
			gsap.from(el, { x: -30, opacity: 0, duration: 1, ease: 'expo.out', scrollTrigger: { trigger: el, start: 'top 90%' } });
		});
		$$('.head .lead, .hs-intro .lead, .services-head .lead').forEach(function (el) {
			gsap.from(el, { y: 30, opacity: 0, duration: 1.2, ease: 'expo.out', scrollTrigger: { trigger: el, start: 'top 90%' } });
		});

		// photos open like a curtain
		$$('[data-clip]').forEach(function (el) {
			var img = $('img', el);
			var tl = gsap.timeline({ scrollTrigger: { trigger: el, start: 'top 85%' } });
			tl.fromTo(el, { clipPath: 'inset(100% 0% 0% 0%)' }, { clipPath: 'inset(0% 0% 0% 0%)', duration: 1.5, ease: 'expo.inOut' });
			if (img && !img.hasAttribute('data-parallax')) tl.from(img, { scale: 1.35, duration: 1.8, ease: 'expo.out' }, .2);
		});
		$$('[data-parallax]').forEach(function (img) {
			gsap.fromTo(img, { yPercent: -8 }, { yPercent: 8, ease: 'none', scrollTrigger: { trigger: img.parentNode, start: 'top bottom', end: 'bottom top', scrub: true } });
		});
		$$('[data-speed]').forEach(function (el) {
			var speed = parseFloat(el.getAttribute('data-speed')) || 0;
			gsap.to(el, { y: speed * 500, ease: 'none', scrollTrigger: { trigger: el, start: 'top bottom', end: 'bottom top', scrub: true } });
		});
		gsap.from('.about-badge', { scale: 0, rotate: -180, duration: 1.4, ease: 'back.out(1.6)', scrollTrigger: { trigger: '.about-media', start: 'top 75%' } });

		var mm = gsap.matchMedia();

		// solutions: vertical scroll moves the panels sideways
		var hs = $('[data-hscroll]');
		if (hs) {
			mm.add('(min-width: 901px)', function () {
				var track = $('[data-hs-track]', hs);
				var bar = $('[data-hs-bar]', hs);
				hs.classList.add('is-h');
				var distance = function () { return Math.max(0, track.scrollWidth - window.innerWidth); };
				var move = gsap.to(track, {
					x: function () { return -distance(); }, ease: 'none',
					scrollTrigger: {
						trigger: hs, start: 'top top', end: function () { return '+=' + distance(); },
						pin: true, scrub: 1, invalidateOnRefresh: true, anticipatePin: 1,
						onUpdate: function (self) { if (bar) bar.style.transform = 'scaleX(' + self.progress.toFixed(3) + ')'; }
					}
				});
				// the big numbers and photos drift inside their cards
				$$('.hs-card', hs).forEach(function (card) {
					gsap.fromTo($('.hs-num', card), { xPercent: 40 }, { xPercent: -40, ease: 'none', scrollTrigger: { trigger: card, containerAnimation: move, start: 'left right', end: 'right left', scrub: true } });
					gsap.fromTo($('.hs-stack', card), { x: 120 }, { x: -60, ease: 'none', scrollTrigger: { trigger: card, containerAnimation: move, start: 'left right', end: 'right left', scrub: true } });
				});
				return function () { hs.classList.remove('is-h'); gsap.set(track, { clearProps: 'transform' }); };
			});
			mm.add('(max-width: 900px)', function () {
				$$('.hs-panel', hs).forEach(function (panel) {
					gsap.from(panel, { y: 60, opacity: 0, duration: 1.2, ease: 'expo.out', scrollTrigger: { trigger: panel, start: 'top 88%' } });
				});
			});
		}

		// services: each card shrinks back as the next one slides over it
		var cards = $$('.stack-card');
		cards.forEach(function (card, i) {
			var next = cards[i + 1];
			if (!next) return;
			gsap.to(card, {
				scale: .9 + i * .015, '--dim': .45, ease: 'none',
				scrollTrigger: { trigger: next, start: 'top 75%', end: 'top ' + (120 + (i + 1) * 26) + 'px', scrub: true }
			});
		});
		cards.forEach(function (card) {
			gsap.from($('.stack-bg', card), { yPercent: 60, opacity: 0, duration: 1.4, ease: 'expo.out', scrollTrigger: { trigger: card, start: 'top 80%' } });
		});

		// showroom: the photo strip slides sideways while the page scrolls
		var showTrack = $('#showTrack');
		if (showTrack) {
			mm.add('(min-width: 901px)', function () {
				showTrack.classList.add('is-scrub');
				gsap.fromTo(showTrack, { x: function () { return window.innerWidth * .12; } }, {
					x: function () { return -Math.max(0, showTrack.scrollWidth - window.innerWidth * .9); }, ease: 'none',
					scrollTrigger: { trigger: showTrack, start: 'top bottom', end: 'bottom top', scrub: 1, invalidateOnRefresh: true }
				});
				$$('.show-item img', showTrack).forEach(function (img) {
					gsap.fromTo(img, { xPercent: -6 }, { xPercent: 6, ease: 'none', scrollTrigger: { trigger: showTrack, start: 'top bottom', end: 'bottom top', scrub: true } });
				});
				return function () { showTrack.classList.remove('is-scrub'); gsap.set(showTrack, { clearProps: 'transform' }); };
			});
		}

		// names band tilts a bit with the scroll
		var namesBand = $('.names');
		if (namesBand) gsap.fromTo(namesBand, { rotate: -4 }, { rotate: 1, ease: 'none', scrollTrigger: { trigger: namesBand, start: 'top bottom', end: 'bottom top', scrub: true } });

		// call to action circle rolls in
		gsap.from('.cta-circle', { scale: .3, rotate: -120, opacity: 0, duration: 1.6, ease: 'expo.out', scrollTrigger: { trigger: '.cta', start: 'top 70%' } });

		// footer: the giant name rises letter by letter
		var giant = $('[data-split-giant]');
		if (giant) {
			// shrink the giant name until it fits the screen width
			var fitGiant = function () {
				giant.style.fontSize = '';
				var size = parseFloat(getComputedStyle(giant).fontSize);
				var room = giant.parentNode.clientWidth * .96;
				if (giant.scrollWidth > room) giant.style.fontSize = (size * room / giant.scrollWidth).toFixed(1) + 'px';
			};
			fitGiant();
			window.addEventListener('resize', fitGiant);
			if (doc.fonts && doc.fonts.ready) doc.fonts.ready.then(fitGiant);
			var gChars = splitChars(giant);
			gsap.from(gChars, { yPercent: 100, duration: 1.4, ease: 'expo.out', stagger: .05, scrollTrigger: { trigger: giant, start: 'top 98%' } });
		}

		window.addEventListener('load', function () { refreshSoon(50); });
	}

	/* ---------- Intro screen ---------- */
	var loader = $('#loader');
	function startPage() {
		play();
		watchCounters();
		if (heroIntro) heroIntro();
	}
	function finishLoader() {
		if (loader && loader.parentNode) loader.parentNode.removeChild(loader);
		doc.body.classList.remove('is-loading');
		try { sessionStorage.setItem('o3-intro', '1'); } catch (e) {}
	}
	var showLoader = motion && loader && !html.classList.contains('skip-loader') && !html.classList.contains('motion-fallback');
	if (showLoader) {
		if (lenis) lenis.stop();
		var firstImg = $('.hero-slide');
		var imgReady = new Promise(function (resolve) {
			if (!firstImg || firstImg.complete) return resolve();
			firstImg.addEventListener('load', resolve);
			firstImg.addEventListener('error', resolve);
			setTimeout(resolve, 2500);
		});
		var counterState = { v: 0 };
		var numEl = $('#loaderNum');
		var barEl = $('#loaderBar');
		var countDone = new Promise(function (resolve) {
			gsap.to(counterState, {
				v: 100, duration: 1.5, ease: 'power2.inOut',
				onUpdate: function () {
					numEl.textContent = Math.round(counterState.v);
					barEl.style.transform = 'scaleX(' + (counterState.v / 100) + ')';
				},
				onComplete: resolve
			});
		});
		Promise.all([imgReady, countDone]).then(function () {
			var tl = gsap.timeline({ onComplete: finishLoader });
			tl.to('.loader-inner', { y: -40, opacity: 0, duration: .6, ease: 'power3.in' })
				.to('.loader-panels span', { yPercent: -100, duration: 1, ease: 'expo.inOut', stagger: .07 }, .35)
				.add(function () { if (lenis) lenis.start(); startPage(); }, .75);
		});
	} else {
		finishLoader();
		if (motion && location.hash && doc.getElementById(location.hash.slice(1))) {
			// page opened on a section: show the hero as it is
			gsap.set(heroChars, { yPercent: 0, rotate: 0 });
			gsap.set('.hero-eyebrow, .hero-lead, .hero-ar, .hero-actions > *, .hero-foot-inner > *', { y: 0, opacity: 1 });
			play();
			watchCounters();
		} else {
			startPage();
		}
	}

	/* ---------- Magnetic buttons ---------- */
	if (fine && !reduce) {
		$$('[data-magnetic]').forEach(function (el) {
			var strength = el.classList.contains('cta-circle') ? .35 : .25;
			el.addEventListener('mousemove', function (e) {
				var r = el.getBoundingClientRect();
				var x = (e.clientX - r.left - r.width / 2) * strength;
				var y = (e.clientY - r.top - r.height / 2) * strength;
				if (gsap) gsap.to(el, { x: x, y: y, duration: .6, ease: 'power3.out' });
				else el.style.transform = 'translate(' + x + 'px,' + y + 'px)';
			});
			el.addEventListener('mouseleave', function () {
				if (gsap) gsap.to(el, { x: 0, y: 0, duration: 1, ease: 'elastic.out(1, .4)' });
				else el.style.transform = '';
			});
		});
	}

	/* ---------- Cursor ---------- */
	var cursor = $('#cursor');
	if (cursor && fine && !reduce) {
		html.classList.add('has-cursor');
		var label = $('#cursorLabel');
		var mx = -100, my = -100, cx = -100, cy = -100;
		doc.addEventListener('mousemove', function (e) { mx = e.clientX; my = e.clientY; cursor.classList.remove('is-out'); }, { passive: true });
		doc.addEventListener('mouseleave', function () { cursor.classList.add('is-out'); });
		doc.addEventListener('mousedown', function () { cursor.classList.add('is-down'); });
		doc.addEventListener('mouseup', function () { cursor.classList.remove('is-down'); });
		doc.addEventListener('mouseover', function (e) {
			var t = e.target;
			var labelled = t.closest('[data-cursor]');
			var hide = t.closest('[data-cursor-hide], input, textarea, iframe');
			cursor.classList.toggle('is-label', !!labelled);
			cursor.classList.toggle('is-hidden', !!hide);
			cursor.classList.toggle('is-hover', !labelled && !!t.closest('a, button, label'));
			if (labelled) label.textContent = labelled.getAttribute('data-cursor');
		});
		(function follow() {
			cx += (mx - cx) * .2;
			cy += (my - cy) * .2;
			cursor.style.transform = 'translate3d(' + cx.toFixed(1) + 'px,' + cy.toFixed(1) + 'px,0)';
			requestAnimationFrame(follow);
		})();
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
			refreshSoon();
		});
	}

	/* ---------- Clients accordion ---------- */
	$$('[data-acc]').forEach(function (acc) {
		var items = $$('.acc-item', acc);
		items.forEach(function (item) {
			$$('.acc-names li', item).forEach(function (li, k) { li.style.setProperty('--k', k); });
			var btn = $('.acc-btn', item);
			btn.addEventListener('click', function () {
				var open = !item.classList.contains('is-open');
				items.forEach(function (other) {
					var on = other === item && open;
					other.classList.toggle('is-open', on);
					$('.acc-btn', other).setAttribute('aria-expanded', on ? 'true' : 'false');
				});
				refreshSoon(750);
			});
		});
	});

	/* ---------- Ask about a product: fill the subject and open the form ---------- */
	function askAbout(name) {
		var subject = $('#subjectField');
		if (subject) subject.value = 'Inquiry about: ' + name;
		scrollToId('contact');
		var nameField = $('#main-contact-form input[name="name"]');
		if (nameField) setTimeout(function () { nameField.focus({ preventScroll: true }); }, 1200);
	}

	/* ---------- Products: filter, search, load more ---------- */
	var grid = $('#productGrid');
	var matches = [];
	if (grid) {
		var PAGE = 12;
		var pCards = $$('.p-card', grid);
		var tabsEl = $('#tabs');
		var tabs = $$('.tab[data-filter]');
		var ink = $('#tabsInk');
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

		var moveInk = function () {
			var active = $('.tab.is-active', tabsEl);
			if (!ink || !active) return;
			tabsEl.classList.add('has-ink');
			ink.style.width = active.offsetWidth + 'px';
			ink.style.transform = 'translateX(' + active.offsetLeft + 'px)';
		};

		var render = function (append) {
			matches = pCards.filter(function (card) {
				return (filter === '*' || card.getAttribute('data-cat') === filter) &&
					(!query || card.getAttribute('data-search').indexOf(query) !== -1);
			});
			pCards.forEach(function (card) { card.classList.remove('is-shown'); });
			matches.forEach(function (card, i) {
				if (i >= limit) return;
				// only the new cards are staggered, the others stay still
				card.style.setProperty('--n', append ? Math.max(0, i - shownBefore) : i % PAGE);
				card.style.animation = (append && i < shownBefore) ? 'none' : '';
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
			refreshSoon(300);
		};

		var setFilter = function (value) {
			filter = value;
			limit = PAGE;
			tabs.forEach(function (tab) {
				var on = tab.getAttribute('data-filter') === value;
				tab.classList.toggle('is-active', on);
				tab.setAttribute('aria-pressed', on ? 'true' : 'false');
			});
			moveInk();
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
		// category panels and footer links pick a category before scrolling to the catalogue
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

		// cards lean toward the pointer
		if (fine && !reduce) {
			var tilted = null;
			grid.addEventListener('mousemove', function (e) {
				var card = e.target.closest('.p-card');
				if (tilted && tilted !== card) { tilted.style.removeProperty('--rx'); tilted.style.removeProperty('--ry'); }
				tilted = card;
				if (!card) return;
				var r = card.getBoundingClientRect();
				var px = (e.clientX - r.left) / r.width - .5;
				var py = (e.clientY - r.top) / r.height - .5;
				card.style.setProperty('--rx', (py * -8).toFixed(2) + 'deg');
				card.style.setProperty('--ry', (px * 10).toFixed(2) + 'deg');
			});
			grid.addEventListener('mouseleave', function () {
				if (tilted) { tilted.style.removeProperty('--rx'); tilted.style.removeProperty('--ry'); tilted = null; }
			});
		}

		window.addEventListener('resize', moveInk);
		if (doc.fonts && doc.fonts.ready) doc.fonts.ready.then(moveInk);
		moveInk();
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
		lbCount.textContent = pad(lbIndex + 1) + ' / ' + pad(items.length);
	}
	function lbOpen(list, index, isPhoto) {
		if (!lb || !list.length) return;
		items = list;
		lastFocus = doc.activeElement;
		lb.classList.toggle('is-photo', !!isPhoto);
		lb.hidden = false;
		stopScroll();
		lbShow(Math.max(0, index));
		requestAnimationFrame(function () { lb.classList.add('is-open'); });
		$('.lb-close', lb).focus();
	}
	function lbClose(restoreFocus) {
		lb.classList.remove('is-open');
		startScroll();
		setTimeout(function () { lb.hidden = true; lbImg.removeAttribute('src'); }, 350);
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
	if ($('#main-contact-form .alert')) setTimeout(function () { scrollToId('contact', true); }, 100);
})();
