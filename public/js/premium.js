/* Premium interactions: reveal, hero, projects case studies, AI assistant. */
(function () {
  'use strict';

  var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  var csrf = (document.querySelector('meta[name="csrf-token"]') || {}).content || '';
  var $ = function (sel, root) { return (root || document).querySelector(sel); };
  var $$ = function (sel, root) { return Array.prototype.slice.call((root || document).querySelectorAll(sel)); };

  function escapeHtml(s) {
    var d = document.createElement('div');
    d.textContent = s == null ? '' : String(s);
    return d.innerHTML;
  }

  /* ---------------- Reveal on scroll ---------------- */
  function initReveal() {
    var items = $$('.reveal');
    // Legacy sections get reveal too
    $$('.section-header, .bio-card, .focus-card, .company-card, .skill-category-card, .bachelor-card, .secondary-edu-card, .research-card, .contact-method-card, .contact-form-card, .highlight-box').forEach(function (el, i) {
      if (!el.classList.contains('reveal')) {
        el.classList.add('reveal');
        el.style.setProperty('--reveal-delay', (i % 4) * 70 + 'ms');
        items.push(el);
      }
    });
    if (reduceMotion || !('IntersectionObserver' in window)) {
      items.forEach(function (el) { el.classList.add('is-visible'); });
      return;
    }
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) { e.target.classList.add('is-visible'); io.unobserve(e.target); }
      });
    }, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 });
    items.forEach(function (el) { io.observe(el); });
  }

  /* ---------------- Header + progress ---------------- */
  function initScrollChrome() {
    var header = $('.site-header');
    var bar = document.createElement('div');
    bar.className = 'pp-progress';
    document.body.appendChild(bar);
    var ticking = false;
    function update() {
      var h = document.documentElement;
      var max = h.scrollHeight - h.clientHeight;
      bar.style.transform = 'scaleX(' + (max > 0 ? h.scrollTop / max : 0) + ')';
      if (header) header.classList.toggle('is-scrolled', h.scrollTop > 10);
      ticking = false;
    }
    window.addEventListener('scroll', function () {
      if (!ticking) { ticking = true; requestAnimationFrame(update); }
    }, { passive: true });
    update();
  }

  /* ---------------- Typed roles ---------------- */
  function initTyped() {
    var el = $('#ph-typed');
    if (!el || reduceMotion) return;
    var words;
    try { words = JSON.parse(el.getAttribute('data-words')); } catch (e) { return; }
    var wi = 0, ci = words[0].length, deleting = true;
    function tick() {
      var word = words[wi];
      if (deleting) {
        ci--;
        if (ci <= 0) { deleting = false; wi = (wi + 1) % words.length; }
      } else {
        ci++;
        if (ci >= words[wi].length) { deleting = true; el.textContent = words[wi]; return setTimeout(tick, 1900); }
      }
      el.textContent = words[wi].slice(0, Math.max(ci, 0)) || ' ';
      setTimeout(tick, deleting ? 38 : 70);
    }
    setTimeout(tick, 2200);
  }

  /* ---------------- Hero particle network ---------------- */
  function initNetwork() {
    var canvas = $('#ph-net');
    if (!canvas || reduceMotion || !canvas.getContext) return;
    var ctx = canvas.getContext('2d');
    var dpr = Math.min(window.devicePixelRatio || 1, 2);
    var pts = [], w = 0, h = 0, running = true, raf = null;
    var mouse = { x: -9999, y: -9999 };

    function resize() {
      var r = canvas.getBoundingClientRect();
      w = r.width; h = r.height;
      canvas.width = w * dpr; canvas.height = h * dpr;
      ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
      var count = Math.min(70, Math.floor((w * h) / 16000));
      pts = [];
      for (var i = 0; i < count; i++) {
        pts.push({ x: Math.random() * w, y: Math.random() * h, vx: (Math.random() - 0.5) * 0.35, vy: (Math.random() - 0.5) * 0.35 });
      }
    }
    function color(a) {
      var bright = document.documentElement.getAttribute('data-theme') === 'bright';
      return bright ? 'rgba(37, 99, 235,' + a + ')' : 'rgba(96, 165, 250,' + a + ')';
    }
    function frame() {
      if (!running) return;
      ctx.clearRect(0, 0, w, h);
      for (var i = 0; i < pts.length; i++) {
        var p = pts[i];
        p.x += p.vx; p.y += p.vy;
        if (p.x < 0 || p.x > w) p.vx *= -1;
        if (p.y < 0 || p.y > h) p.vy *= -1;
        for (var j = i + 1; j < pts.length; j++) {
          var q = pts[j], dx = p.x - q.x, dy = p.y - q.y, d = dx * dx + dy * dy;
          if (d < 15000) {
            ctx.strokeStyle = color(0.18 * (1 - d / 15000));
            ctx.lineWidth = 1;
            ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(q.x, q.y); ctx.stroke();
          }
        }
        var mdx = p.x - mouse.x, mdy = p.y - mouse.y, md = mdx * mdx + mdy * mdy;
        if (md < 22000) {
          ctx.strokeStyle = color(0.35 * (1 - md / 22000));
          ctx.beginPath(); ctx.moveTo(p.x, p.y); ctx.lineTo(mouse.x, mouse.y); ctx.stroke();
        }
        ctx.fillStyle = color(0.55);
        ctx.beginPath(); ctx.arc(p.x, p.y, 1.4, 0, Math.PI * 2); ctx.fill();
      }
      raf = requestAnimationFrame(frame);
    }
    resize();
    window.addEventListener('resize', resize);
    canvas.parentElement.addEventListener('mousemove', function (e) {
      var r = canvas.getBoundingClientRect();
      mouse.x = e.clientX - r.left; mouse.y = e.clientY - r.top;
    });
    canvas.parentElement.addEventListener('mouseleave', function () { mouse.x = mouse.y = -9999; });
    // Pause when hero is off screen or tab hidden
    if ('IntersectionObserver' in window) {
      new IntersectionObserver(function (entries) {
        running = entries[0].isIntersecting && !document.hidden;
        if (running && !raf) frame();
        if (!running && raf) { cancelAnimationFrame(raf); raf = null; }
      }).observe(canvas);
    }
    document.addEventListener('visibilitychange', function () {
      running = !document.hidden;
      if (running && !raf) frame();
      if (!running && raf) { cancelAnimationFrame(raf); raf = null; }
    });
    frame();
  }

  /* ---------------- Count-up stats ---------------- */
  function initCounters() {
    var els = $$('[data-count]');
    if (!els.length || reduceMotion || !('IntersectionObserver' in window)) return;
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (!e.isIntersecting) return;
        io.unobserve(e.target);
        var el = e.target, end = parseInt(el.getAttribute('data-count'), 10) || 0, suffix = el.getAttribute('data-suffix') || '';
        var start = performance.now();
        (function step(t) {
          var k = Math.min(1, (t - start) / 1200);
          el.textContent = Math.round(end * (1 - Math.pow(1 - k, 3))) + suffix;
          if (k < 1) requestAnimationFrame(step);
        })(start);
      });
    });
    els.forEach(function (el) { io.observe(el); });
  }

  /* ---------------- Projects: filter, case study modal ---------------- */
  function initProjects() {
    var filters = $$('.pp-filter');
    var cards = $$('.pp-card');
    filters.forEach(function (btn) {
      btn.addEventListener('click', function () {
        var f = btn.getAttribute('data-pp-filter');
        filters.forEach(function (b) { b.classList.toggle('active', b === btn); b.setAttribute('aria-selected', b === btn ? 'true' : 'false'); });
        cards.forEach(function (c) {
          var show = f === 'all' || c.getAttribute('data-pp-cat') === f;
          c.classList.toggle('is-hidden', !show);
          if (show) c.classList.add('is-visible');
        });
      });
    });

    var modal = $('#pc-modal');
    var content = $('#pc-content');
    if (!modal || !content) return;
    var lastFocus = null;

    function open(slug) {
      var tpl = document.getElementById('pp-case-' + slug);
      if (!tpl) return;
      lastFocus = document.activeElement;
      content.innerHTML = '';
      content.appendChild(tpl.content.cloneNode(true));
      content.scrollTop = 0;
      modal.classList.add('is-open');
      modal.setAttribute('aria-hidden', 'false');
      document.body.classList.add('pc-locked');
      initGallery(content);
      var close = $('.pc-close', modal);
      if (close) close.focus();
      if (history.replaceState) history.replaceState(null, '', '#project-' + slug);
    }
    function closeModal() {
      modal.classList.remove('is-open');
      modal.setAttribute('aria-hidden', 'true');
      document.body.classList.remove('pc-locked');
      content.innerHTML = '';
      if (lastFocus) lastFocus.focus();
    }
    document.addEventListener('click', function (e) {
      var opener = e.target.closest('[data-pp-open]');
      if (opener) { e.preventDefault(); open(opener.getAttribute('data-pp-open')); return; }
      if (e.target.closest('[data-pc-close]')) closeModal();
    });
    document.addEventListener('keydown', function (e) {
      if (!modal.classList.contains('is-open')) return;
      if (e.key === 'Escape') closeModal();
      if (e.key === 'ArrowRight') stepGallery(1);
      if (e.key === 'ArrowLeft') stepGallery(-1);
    });
    // Deep link: /#project-doctor-qr opens the case study
    var m = location.hash.match(/^#project-([a-z0-9-]+)$/);
    if (m && document.getElementById('pp-case-' + m[1])) {
      setTimeout(function () { open(m[1]); }, 400);
    }
    window.__ppOpenProject = open;
  }

  var galleryState = null;
  function initGallery(root) {
    var g = $('[data-pc-gallery]', root);
    galleryState = null;
    if (!g) return;
    var thumbs = $$('[data-pc-thumb]', g), main = $('[data-pc-main]', g), cap = $('[data-pc-caption]', g);
    galleryState = { i: 0, thumbs: thumbs, main: main, cap: cap };
    thumbs.forEach(function (t, i) { t.addEventListener('click', function () { showGallery(i); }); });
    $$('[data-pc-step]', g).forEach(function (b) { b.addEventListener('click', function () { stepGallery(parseInt(b.getAttribute('data-pc-step'), 10)); }); });
    // Swipe on touch devices
    var sx = null;
    main.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; }, { passive: true });
    main.addEventListener('touchend', function (e) {
      if (sx === null) return;
      var dx = e.changedTouches[0].clientX - sx;
      if (Math.abs(dx) > 40) stepGallery(dx < 0 ? 1 : -1);
      sx = null;
    });
  }
  function showGallery(i) {
    var s = galleryState;
    if (!s || !s.thumbs.length) return;
    s.i = (i + s.thumbs.length) % s.thumbs.length;
    var t = s.thumbs[s.i];
    s.main.style.opacity = '0';
    setTimeout(function () {
      s.main.src = t.getAttribute('data-src');
      s.main.alt = t.getAttribute('data-caption');
      s.cap.textContent = t.getAttribute('data-caption');
      s.main.style.opacity = '1';
    }, 140);
    s.thumbs.forEach(function (b, k) { b.classList.toggle('active', k === s.i); });
    t.scrollIntoView({ block: 'nearest', inline: 'nearest' });
  }
  function stepGallery(d) { if (galleryState) showGallery(galleryState.i + d); }

  /* ---------------- Outbound project click tracking ---------------- */
  function initTracking() {
    document.addEventListener('click', function (e) {
      var a = e.target.closest('[data-track-project]');
      if (!a || !window.fetch) return;
      try {
        fetch('/track/click', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
          body: JSON.stringify({ project_slug: a.getAttribute('data-track-project'), click_type: a.getAttribute('data-track-type') || 'external' }),
          keepalive: true
        });
      } catch (err) { /* tracking is best-effort */ }
    });
  }

  /* ---------------- AI assistant widget ---------------- */
  function initAssistant() {
    var widget = $('.pw-widget');
    if (!widget) return;
    var launcher = $('#chat-bubble-btn');
    var panel = $('#chat-window');
    var tabs = $$('.pw-tab', widget);
    var panes = $$('.pw-pane', widget);
    var body = $('#assist-body');
    var form = $('#assist-form');
    var input = $('#assist-input');
    var busy = false;

    function setTab(name) {
      tabs.forEach(function (t) {
        var on = t.getAttribute('data-pw-tab') === name;
        t.classList.toggle('active', on);
        t.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      panes.forEach(function (p) { p.classList.toggle('active', p.getAttribute('data-pw-pane') === name); });
      var focusEl = name === 'assist' ? input : $('#chat-text-input');
      if (focusEl && window.innerWidth > 640) setTimeout(function () { focusEl.focus(); }, 60);
    }
    tabs.forEach(function (t) { t.addEventListener('click', function () { setTab(t.getAttribute('data-pw-tab')); }); });

    function isOpen() { return panel && panel.style.display !== 'none'; }
    if (launcher) launcher.addEventListener('click', function () { widget.classList.remove('show-hint'); });
    function openWidget(tab) {
      if (!isOpen() && launcher) launcher.click();
      setTab(tab || 'assist');
    }
    window.__pwOpen = openWidget;

    // Mirror online / away state to the tab dot
    var indicator = $('#chat-status-indicator');
    if (indicator && 'MutationObserver' in window) {
      var sync = function () { widget.classList.toggle('is-away', indicator.classList.contains('offline')); };
      new MutationObserver(sync).observe(indicator, { attributes: true, attributeFilter: ['class'] });
      sync();
    }

    // Gentle hint after a few seconds, once per visit
    try {
      if (!sessionStorage.getItem('pw_hint')) {
        setTimeout(function () {
          if (!isOpen()) { widget.classList.add('show-hint'); setTimeout(function () { widget.classList.remove('show-hint'); }, 5000); }
          sessionStorage.setItem('pw_hint', '1');
        }, 6000);
      }
    } catch (e) {}

    function format(text) {
      // Escape first, then apply a tiny safe markdown subset: **bold**, bullets, line breaks
      var lines = escapeHtml(text).split('\n');
      var html = '', inList = false;
      lines.forEach(function (line) {
        var l = line.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');
        if (/^\s*•\s?/.test(l)) {
          if (!inList) { html += '<ul>'; inList = true; }
          html += '<li>' + l.replace(/^\s*•\s?/, '') + '</li>';
        } else {
          if (inList) { html += '</ul>'; inList = false; }
          if (l.trim()) html += '<p>' + l + '</p>';
        }
      });
      if (inList) html += '</ul>';
      return html;
    }

    function safeUrl(u) {
      if (typeof u !== 'string') return null;
      if (/^(https?:|mailto:|#|\/)/i.test(u)) return u;
      return null;
    }

    function addMessage(who, html) {
      var chips = $('#assist-chips');
      if (chips) chips.remove();
      var row = document.createElement('div');
      row.className = 'pw-msg ' + who;
      row.innerHTML = (who === 'bot' ? '<div class="pw-bot-icon" aria-hidden="true">AI</div>' : '') + '<div class="pw-bubble">' + html + '</div>';
      body.appendChild(row);
      body.scrollTop = body.scrollHeight;
      return row;
    }

    function addChips(list) {
      if (!list || !list.length) return;
      var wrap = document.createElement('div');
      wrap.className = 'pw-chips';
      wrap.id = 'assist-chips';
      list.forEach(function (s) {
        var b = document.createElement('button');
        b.type = 'button'; b.className = 'pw-chip'; b.textContent = s;
        wrap.appendChild(b);
      });
      body.appendChild(wrap);
      body.scrollTop = body.scrollHeight;
    }

    function ask(question) {
      question = (question || '').trim();
      if (!question || busy) return;
      busy = true;
      addMessage('user', '<p>' + escapeHtml(question) + '</p>');
      var typing = addMessage('bot', '<span class="pw-typing" aria-label="Assistant is typing"><i></i><i></i><i></i></span>');
      var started = Date.now();
      fetch('/api/assistant', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: JSON.stringify({ question: question })
      })
        .then(function (r) {
          if (r.status === 429) throw new Error('rate');
          if (!r.ok) throw new Error('http');
          return r.json();
        })
        .then(function (data) {
          var wait = Math.max(0, 450 - (Date.now() - started));
          setTimeout(function () {
            var html = format(data.answer || '');
            var links = (data.links || []).map(function (l) {
              var u = safeUrl(l.url);
              if (!u) return '';
              var ext = /^https?:/i.test(u) ? ' target="_blank" rel="noopener noreferrer"' : '';
              return '<a href="' + escapeHtml(u) + '"' + ext + ' data-pw-link>' + escapeHtml(l.label) + '</a>';
            }).join('');
            if (links) html += '<div class="pw-links">' + links + '</div>';
            typing.querySelector('.pw-bubble').innerHTML = html;
            addChips(data.suggestions);
            if (data.open_live_chat) {
              addChips(['Message Hasnain now']);
            }
            body.scrollTop = body.scrollHeight;
            busy = false;
          }, wait);
        })
        .catch(function (err) {
          typing.querySelector('.pw-bubble').innerHTML = '<p>' + (err.message === 'rate'
            ? 'You are asking very fast. Please wait a minute and try again.'
            : 'Sorry, I could not answer right now. You can message Hasnain directly in the other tab.') + '</p>';
          busy = false;
        });
    }

    if (form) {
      form.addEventListener('submit', function (e) {
        e.preventDefault();
        var q = input.value;
        input.value = '';
        ask(q);
      });
    }
    body.addEventListener('click', function (e) {
      var chip = e.target.closest('.pw-chip');
      if (chip) {
        if (chip.textContent === 'Message Hasnain now') { setTab('live'); return; }
        ask(chip.textContent);
        return;
      }
      var link = e.target.closest('[data-pw-link]');
      if (link) {
        var href = link.getAttribute('href') || '';
        var pm = href.match(/^#project-([a-z0-9-]+)$/);
        if (pm && window.__ppOpenProject) { e.preventDefault(); window.__ppOpenProject(pm[1]); return; }
        if (href.charAt(0) === '#' && window.innerWidth <= 640 && launcher) launcher.click();
      }
    });

    // Buttons elsewhere on the page that open or prefill the assistant
    document.addEventListener('click', function (e) {
      var opener = e.target.closest('[data-pw-open]');
      if (opener) { e.preventDefault(); openWidget(opener.getAttribute('data-pw-open')); return; }
      var asker = e.target.closest('[data-pw-ask]');
      if (asker) {
        e.preventDefault();
        var closeBtn = document.querySelector('.pc-modal.is-open [data-pc-close]');
        if (closeBtn) closeBtn.click();
        openWidget('assist');
        ask(asker.getAttribute('data-pw-ask'));
      }
    });
  }

  function boot() {
    document.documentElement.classList.remove('no-js');
    initReveal();
    initScrollChrome();
    initTyped();
    initNetwork();
    initCounters();
    initProjects();
    initTracking();
    initAssistant();
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
