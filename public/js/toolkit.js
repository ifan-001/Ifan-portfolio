/* Developer toolkit: all processing happens locally in the browser. */
(function () {
  'use strict';
  var $ = function (id) { return document.getElementById(id); };

  // Tabs
  var tabs = Array.prototype.slice.call(document.querySelectorAll('.tk-tab'));
  var panels = Array.prototype.slice.call(document.querySelectorAll('.tk-panel'));
  function show(id) {
    tabs.forEach(function (t) { var on = t.dataset.tool === id; t.classList.toggle('active', on); t.setAttribute('aria-selected', on ? 'true' : 'false'); });
    panels.forEach(function (p) { p.classList.toggle('active', p.dataset.panel === id); });
    if (history.replaceState) history.replaceState(null, '', '#' + id);
    if (id === 'qr') renderQr();
    if (id === 'password' && $('tk-pass-out').textContent === '—') genPass();
  }
  tabs.forEach(function (t) { t.addEventListener('click', function () { show(t.dataset.tool); }); });

  // Copy buttons
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-copy]'); if (!b) return;
    var text = $(b.dataset.copy).textContent;
    if (!text || text === '—') return;
    (navigator.clipboard ? navigator.clipboard.writeText(text) : Promise.reject()).then(function () {
      var old = b.textContent; b.textContent = 'Copied ✓'; setTimeout(function () { b.textContent = old; }, 1200);
    }).catch(function () {});
  });

  // JSON
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-json]'); if (!b) return;
    var msg = $('tk-json-msg'), out = $('tk-json-out');
    try {
      var obj = JSON.parse($('tk-json-in').value);
      out.textContent = b.dataset.json === 'format' ? JSON.stringify(obj, null, 2) : JSON.stringify(obj);
      msg.textContent = 'Valid JSON ✓'; msg.className = 'tk-msg ok';
    } catch (err) {
      out.textContent = ''; msg.textContent = err.message; msg.className = 'tk-msg err';
    }
  });

  // Password
  function genPass() {
    var sets = '';
    if ($('tk-p-upper').checked) sets += 'ABCDEFGHJKLMNPQRSTUVWXYZ';
    if ($('tk-p-lower').checked) sets += 'abcdefghijkmnopqrstuvwxyz';
    if ($('tk-p-num').checked) sets += '23456789';
    if ($('tk-p-sym').checked) sets += '!@#$%^&*()-_=+[]{};:,.?';
    if (!sets) { $('tk-pass-out').textContent = 'Pick at least one set'; return; }
    var len = +$('tk-pass-len').value, arr = new Uint32Array(len), out = '';
    crypto.getRandomValues(arr);
    for (var i = 0; i < len; i++) out += sets[arr[i] % sets.length];
    $('tk-pass-out').textContent = out;
    var bits = len * Math.log2(sets.length), pct = Math.min(100, bits / 1.28);
    var m = $('tk-pass-meter'); m.style.width = pct + '%';
    m.style.background = bits < 50 ? '#EF4444' : bits < 80 ? '#F59E0B' : '#10B981';
    m.title = Math.round(bits) + ' bits of entropy';
  }
  $('tk-pass-gen').addEventListener('click', genPass);
  $('tk-pass-len').addEventListener('input', function () { $('tk-pass-len-v').textContent = this.value; genPass(); });
  ['tk-p-upper', 'tk-p-lower', 'tk-p-num', 'tk-p-sym'].forEach(function (id) { $(id).addEventListener('change', genPass); });

  // QR
  var qrTimer;
  function renderQr() {
    var box = $('tk-qr-out'); if (!window.QRCode) return;
    box.innerHTML = '';
    var text = $('tk-qr-in').value.trim() || ' ';
    var size = +$('tk-qr-size').value;
    try { new QRCode(box, { text: text, width: size, height: size, colorDark: '#0B1120', colorLight: '#ffffff', correctLevel: QRCode.CorrectLevel.M }); }
    catch (e) { box.textContent = 'Text is too long for a QR code.'; }
  }
  $('tk-qr-in').addEventListener('input', function () { clearTimeout(qrTimer); qrTimer = setTimeout(renderQr, 250); });
  $('tk-qr-size').addEventListener('input', renderQr);
  $('tk-qr-dl').addEventListener('click', function () {
    var c = $('tk-qr-out').querySelector('canvas'), img = $('tk-qr-out').querySelector('img');
    var url = c ? c.toDataURL('image/png') : img ? img.src : null;
    if (!url) return;
    var a = document.createElement('a'); a.href = url; a.download = 'qr-code.png'; a.click();
  });

  // Words
  $('tk-words-in').addEventListener('input', function () {
    var t = this.value, words = (t.trim().match(/\S+/g) || []).length;
    var sentences = (t.match(/[.!?।]+(\s|$)/g) || []).length;
    var stats = [['Words', words], ['Characters', t.length], ['No spaces', t.replace(/\s/g, '').length], ['Sentences', sentences], ['Paragraphs', (t.split(/\n\s*\n/).filter(function (p) { return p.trim(); })).length], ['Reading time', Math.max(1, Math.round(words / 200)) + ' min']];
    $('tk-words-stats').innerHTML = stats.map(function (s) { return '<div><strong>' + s[1] + '</strong><span>' + s[0] + '</span></div>'; }).join('');
  });
  $('tk-words-in').dispatchEvent(new Event('input'));

  // Encode
  document.addEventListener('click', function (e) {
    var b = e.target.closest('[data-enc]'); if (!b) return;
    var v = $('tk-enc-in').value, out = $('tk-enc-out');
    try {
      if (b.dataset.enc === 'b64e') out.textContent = btoa(unescape(encodeURIComponent(v)));
      if (b.dataset.enc === 'b64d') out.textContent = decodeURIComponent(escape(atob(v.trim())));
      if (b.dataset.enc === 'urle') out.textContent = encodeURIComponent(v);
      if (b.dataset.enc === 'urld') out.textContent = decodeURIComponent(v);
    } catch (err) { out.textContent = 'Could not decode: input is not valid for this format.'; }
  });

  // Contrast
  function lum(hex) {
    var c = hex.replace('#', ''); if (c.length === 3) c = c.split('').map(function (x) { return x + x; }).join('');
    var rgb = [0, 2, 4].map(function (i) { var v = parseInt(c.substr(i, 2), 16) / 255; return v <= 0.03928 ? v / 12.92 : Math.pow((v + 0.055) / 1.055, 2.4); });
    return 0.2126 * rgb[0] + 0.7152 * rgb[1] + 0.0722 * rgb[2];
  }
  function contrast() {
    var fg = $('tk-fg').value, bg = $('tk-bg').value;
    var a = lum(fg), b = lum(bg), ratio = (Math.max(a, b) + 0.05) / (Math.min(a, b) + 0.05);
    $('tk-demo').style.color = fg; $('tk-demo').style.background = bg;
    var pass = function (x) { return x ? '<em class="ok">Pass</em>' : '<em class="err">Fail</em>'; };
    $('tk-contrast-stats').innerHTML = '<div><strong>' + ratio.toFixed(2) + ':1</strong><span>Contrast ratio</span></div>' +
      '<div><strong>' + pass(ratio >= 4.5) + '</strong><span>AA normal text</span></div>' +
      '<div><strong>' + pass(ratio >= 3) + '</strong><span>AA large text</span></div>' +
      '<div><strong>' + pass(ratio >= 7) + '</strong><span>AAA normal text</span></div>';
  }
  [['tk-fg', 'tk-fg-t'], ['tk-bg', 'tk-bg-t']].forEach(function (p) {
    $(p[0]).addEventListener('input', function () { $(p[1]).value = this.value.toUpperCase(); contrast(); });
    $(p[1]).addEventListener('input', function () { if (/^#[0-9a-f]{6}$/i.test(this.value)) { $(p[0]).value = this.value; contrast(); } });
  });
  contrast();

  // Timestamp
  function tsOut() {
    var v = $('tk-ts').value.trim(); if (!/^\d+$/.test(v)) { $('tk-ts-out').innerHTML = ''; return; }
    var ms = v.length > 11 ? +v : +v * 1000, d = new Date(ms);
    $('tk-ts-out').innerHTML = '<div><strong>' + d.toLocaleString() + '</strong><span>Local time</span></div><div><strong>' + d.toISOString() + '</strong><span>UTC (ISO 8601)</span></div>';
  }
  $('tk-ts').addEventListener('input', tsOut);
  $('tk-ts-now').addEventListener('click', function () { $('tk-ts').value = Math.floor(Date.now() / 1000); tsOut(); });
  $('tk-dt').addEventListener('input', function () {
    var d = new Date(this.value); if (isNaN(d)) return;
    $('tk-dt-out').innerHTML = '<div><strong>' + Math.floor(d.getTime() / 1000) + '</strong><span>Seconds</span></div><div><strong>' + d.getTime() + '</strong><span>Milliseconds</span></div>';
  });
  $('tk-ts-now').click();

  var hash = (location.hash || '').slice(1);
  show(tabs.some(function (t) { return t.dataset.tool === hash; }) ? hash : tabs[0].dataset.tool);
})();
