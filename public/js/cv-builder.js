/* CV Builder: form state -> six templates -> A4 print/PDF. All data stays in localStorage. */
(function () {
  'use strict';
  var KEY = 'cvb_state_v1';
  var TEMPLATES = [
    { id: 'modern', name: 'Modern', thumb: [['0', '0', '34%', '100%', 'var(--t)'], ['42%', '10%', '45%', '7%'], ['42%', '22%', '50%', '4%'], ['42%', '32%', '50%', '4%'], ['42%', '42%', '40%', '4%']] },
    { id: 'executive', name: 'Executive', thumb: [['25%', '8%', '50%', '8%'], ['10%', '22%', '80%', '2%', 'var(--t)'], ['10%', '30%', '80%', '4%'], ['10%', '40%', '80%', '4%'], ['10%', '50%', '60%', '4%']] },
    { id: 'minimal', name: 'Minimal', thumb: [['10%', '10%', '45%', '8%'], ['72%', '8%', '18%', '22%'], ['10%', '38%', '12%', '2%', 'var(--t)'], ['10%', '44%', '75%', '4%'], ['10%', '54%', '70%', '4%']] },
    { id: 'creative', name: 'Creative', thumb: [['0', '0', '100%', '30%', 'var(--t)'], ['8%', '40%', '55%', '4%'], ['8%', '50%', '55%', '4%'], ['70%', '40%', '22%', '4%'], ['70%', '50%', '22%', '4%']] },
    { id: 'tech', name: 'Tech', thumb: [['0', '0', '100%', '28%', '#0F172A'], ['8%', '10%', '40%', '6%', 'var(--t)'], ['8%', '38%', '80%', '4%'], ['8%', '48%', '80%', '4%'], ['8%', '58%', '60%', '4%']] },
    { id: 'ats', name: 'ATS Plain', thumb: [['10%', '8%', '50%', '7%', '#111'], ['10%', '22%', '80%', '1.5%', '#111'], ['10%', '28%', '80%', '4%'], ['10%', '38%', '80%', '4%'], ['10%', '48%', '80%', '4%']] }
  ];
  var LISTS = {
    experience: [['role', 'Role / Position'], ['company', 'Company'], ['period', 'Period (e.g. 2024 – Present)'], ['location', 'Location'], ['points', 'Achievements (one per line)', 'textarea']],
    projects: [['name', 'Project name'], ['tech', 'Tech used'], ['link', 'Link'], ['desc', 'What it does / your impact', 'textarea']],
    education: [['degree', 'Degree'], ['school', 'Institution'], ['period', 'Year / Period'], ['score', 'CGPA / Grade']]
  };
  var EMPTY = { template: 'modern', accent: '#2563EB', photo: '', name: '', title: '', email: '', phone: '', location: '', website: '', linkedin: '', github: '', summary: '', skills: '', languages: '', certifications: '', experience: [{}], projects: [{}], education: [{}] };
  var SAMPLE = {
    template: 'modern', accent: '#2563EB', photo: '',
    name: 'Ayesha Rahman', title: 'Software Engineer · Laravel & Vue',
    email: 'ayesha.rahman@email.com', phone: '+880 1712-000000', location: 'Dhaka, Bangladesh',
    website: 'ayesha.dev', linkedin: 'linkedin.com/in/ayesha', github: 'github.com/ayesha',
    summary: 'Software engineer with 3 years of experience building multi-tenant SaaS products in Laravel and Vue. I enjoy turning messy business workflows into fast, tested and well-documented software.',
    skills: 'PHP, Laravel, Vue.js, MySQL, Redis, REST APIs, Docker, Git, PHPUnit, Tailwind CSS',
    languages: 'Bengali (Native), English (Professional)',
    certifications: 'AWS Cloud Practitioner (2025)\nWinner, BUBT Inter-University Hackathon (2023)',
    experience: [
      { role: 'Software Engineer', company: 'TechNova Ltd.', period: '2024 – Present', location: 'Dhaka', points: 'Built a billing module used by 1,200+ clinics with Laravel queues and Stripe\nCut page load time by 45% with query optimisation and Redis caching\nMentored 2 interns and introduced code review checklists' },
      { role: 'Junior Developer', company: 'PixelSoft', period: '2022 – 2024', location: 'Dhaka', points: 'Developed REST APIs for a Flutter delivery app\nWrote PHPUnit tests that raised coverage from 20% to 70%' }
    ],
    projects: [
      { name: 'ClinicFlow', tech: 'Laravel, Vue, MySQL', link: 'clinicflow.app', desc: 'Appointment and queue system with SMS reminders and online payments.' },
      { name: 'ShopLite', tech: 'Node.js, React, MongoDB', link: '', desc: 'Lightweight e-commerce storefront with cart, search and admin dashboard.' }
    ],
    education: [
      { degree: 'B.Sc. in Computer Science & Engineering', school: 'Bangladesh University of Business & Technology', period: '2018 – 2022', score: 'CGPA 3.60 / 4.00' },
      { degree: 'Higher Secondary Certificate (Science)', school: 'Dhaka City College', period: '2017', score: 'GPA 5.00 / 5.00' }
    ]
  };

  var state = load();
  var form = document.getElementById('cvb-form');
  var paper = document.getElementById('cv-paper');
  var scaleBox = document.getElementById('cvb-scale');
  if (!form || !paper) return;

  function clone(o) { return JSON.parse(JSON.stringify(o)); }
  function load() {
    try { var s = JSON.parse(localStorage.getItem(KEY)); if (s && s.template) return Object.assign(clone(EMPTY), s); } catch (e) {}
    return clone(EMPTY);
  }
  var saveTimer = null;
  function save() {
    clearTimeout(saveTimer);
    saveTimer = setTimeout(function () {
      try { localStorage.setItem(KEY, JSON.stringify(state)); }
      catch (e) { try { var s = clone(state); s.photo = ''; localStorage.setItem(KEY, JSON.stringify(s)); } catch (e2) {} }
    }, 250);
  }
  function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : String(s); return d.innerHTML; }
  function list(str, sep) { return String(str || '').split(sep || ',').map(function (s) { return s.trim(); }).filter(Boolean); }
  function filled(arr, keys) { return (arr || []).filter(function (it) { return keys.some(function (k) { return it[k] && String(it[k]).trim(); }); }); }

  /* ---------- Template picker ---------- */
  var tplRow = document.getElementById('cvb-templates');
  TEMPLATES.forEach(function (t) {
    var b = document.createElement('button');
    b.type = 'button'; b.className = 'cvb-tpl'; b.dataset.tpl = t.id;
    var thumb = '<div class="cvb-tpl-thumb">' + t.thumb.map(function (r) {
      return '<i style="left:' + r[0] + ';top:' + r[1] + ';width:' + r[2] + ';height:' + r[3] + ';' + (r[4] ? 'background:' + r[4] : '') + '"></i>';
    }).join('') + '</div>';
    b.innerHTML = thumb + esc(t.name);
    b.addEventListener('click', function () { state.template = t.id; syncPicker(); render(); save(); });
    tplRow.appendChild(b);
  });
  function syncPicker() {
    Array.prototype.forEach.call(tplRow.children, function (b) {
      b.classList.toggle('active', b.dataset.tpl === state.template);
      b.style.setProperty('--t', state.accent);
    });
  }
  var accent = document.getElementById('cvb-accent');
  accent.addEventListener('input', function () { state.accent = accent.value; syncPicker(); render(); save(); });

  /* ---------- Form binding ---------- */
  function bindFields() {
    Array.prototype.forEach.call(form.querySelectorAll('[data-field]'), function (el) {
      el.value = state[el.dataset.field] || '';
    });
    accent.value = state.accent || '#2563EB';
    Object.keys(LISTS).forEach(renderList);
    showPhoto();
  }
  form.addEventListener('input', function (e) {
    var el = e.target;
    if (el.dataset.field) state[el.dataset.field] = el.value;
    if (el.dataset.list) state[el.dataset.list][+el.dataset.index][el.dataset.key] = el.value;
    render(); save();
  });

  function renderList(name) {
    var box = form.querySelector('[data-list="' + name + '"] .cvb-items');
    box.innerHTML = '';
    if (!state[name] || !state[name].length) state[name] = [{}];
    state[name].forEach(function (item, i) {
      var div = document.createElement('div');
      div.className = 'cvb-item';
      var html = '<div class="cvb-item-head"><span>#' + (i + 1) + '</span><button type="button" data-remove="' + name + '" data-index="' + i + '">Remove</button></div>';
      LISTS[name].forEach(function (f) {
        var attrs = ' data-list="' + name + '" data-index="' + i + '" data-key="' + f[0] + '"';
        html += '<label>' + esc(f[1]) + (f[2] === 'textarea'
          ? '<textarea rows="3"' + attrs + '>' + esc(item[f[0]] || '') + '</textarea>'
          : '<input' + attrs + ' value="' + esc(item[f[0]] || '') + '">') + '</label>';
      });
      div.innerHTML = html;
      box.appendChild(div);
    });
  }
  form.addEventListener('click', function (e) {
    var add = e.target.closest('[data-add]');
    if (add) { state[add.dataset.add].push({}); renderList(add.dataset.add); render(); save(); return; }
    var rm = e.target.closest('[data-remove]');
    if (rm) { state[rm.dataset.remove].splice(+rm.dataset.index, 1); renderList(rm.dataset.remove); render(); save(); }
  });

  /* ---------- Photo (resized client side) ---------- */
  var photoInput = document.getElementById('cvb-photo');
  photoInput.addEventListener('change', function () {
    var file = photoInput.files && photoInput.files[0];
    if (!file || !/^image\//.test(file.type)) return;
    var reader = new FileReader();
    reader.onload = function () {
      var img = new Image();
      img.onload = function () {
        var max = 520, w = img.width, h = img.height, r = Math.min(1, max / Math.max(w, h));
        var c = document.createElement('canvas');
        c.width = Math.round(w * r); c.height = Math.round(h * r);
        c.getContext('2d').drawImage(img, 0, 0, c.width, c.height);
        state.photo = c.toDataURL('image/jpeg', 0.86);
        showPhoto(); render(); save();
      };
      img.src = reader.result;
    };
    reader.readAsDataURL(file);
  });
  document.getElementById('cvb-photo-remove').addEventListener('click', function () { state.photo = ''; photoInput.value = ''; showPhoto(); render(); save(); });
  function showPhoto() {
    var p = document.getElementById('cvb-photo-preview');
    p.style.backgroundImage = state.photo ? 'url(' + state.photo + ')' : '';
    p.textContent = state.photo ? '' : '+';
  }

  /* ---------- Rendering blocks ---------- */
  function photoBox() { return state.photo ? '<div class="cv-photo-box"><img class="cv-photo" src="' + state.photo + '" alt=""></div>' : ''; }
  function contact() {
    var items = [state.email, state.phone, state.location, state.website, state.linkedin, state.github].filter(Boolean);
    return items.length ? '<div class="cv-contact">' + items.map(function (c) { return '<span>' + esc(c) + '</span>'; }).join('') + '</div>' : '';
  }
  function sec(title, inner) { return inner ? '<section class="cv-section"><h2 class="cv-h">' + title + '</h2>' + inner + '</section>' : ''; }
  function summary() { return state.summary ? sec('Profile', '<p>' + esc(state.summary) + '</p>') : ''; }
  function experience() {
    var items = filled(state.experience, ['role', 'company']);
    return sec('Experience', items.map(function (x) {
      var pts = list(x.points, '\n');
      return '<div class="cv-entry"><div class="cv-entry-top"><div><div class="cv-entry-title">' + esc(x.role) + '</div><div class="cv-entry-sub">' + esc([x.company, x.location].filter(Boolean).join(' · ')) + '</div></div><div class="cv-entry-date">' + esc(x.period) + '</div></div>' +
        (pts.length ? '<ul>' + pts.map(function (p) { return '<li>' + esc(p) + '</li>'; }).join('') + '</ul>' : '') + '</div>';
    }).join(''));
  }
  function projects() {
    var items = filled(state.projects, ['name']);
    return sec('Projects', items.map(function (x) {
      return '<div class="cv-entry"><div class="cv-entry-top"><div class="cv-entry-title">' + esc(x.name) + (x.tech ? ' <span class="cv-entry-sub">· ' + esc(x.tech) + '</span>' : '') + '</div><div class="cv-entry-date">' + esc(x.link) + '</div></div>' + (x.desc ? '<p>' + esc(x.desc) + '</p>' : '') + '</div>';
    }).join(''));
  }
  function education() {
    var items = filled(state.education, ['degree', 'school']);
    return sec('Education', items.map(function (x) {
      return '<div class="cv-entry"><div class="cv-entry-top"><div><div class="cv-entry-title">' + esc(x.degree) + '</div><div class="cv-entry-sub">' + esc(x.school) + (x.score ? ' · ' + esc(x.score) : '') + '</div></div><div class="cv-entry-date">' + esc(x.period) + '</div></div></div>';
    }).join(''));
  }
  function tags(title, str) { var l = list(str); return l.length ? sec(title, '<div class="cv-tags">' + l.map(function (s) { return '<span>' + esc(s) + '</span>'; }).join('') + '</div>') : ''; }
  function certs() { var l = list(state.certifications, '\n'); return l.length ? sec('Certifications & Awards', '<ul>' + l.map(function (s) { return '<li>' + esc(s) + '</li>'; }).join('') + '</ul>') : ''; }
  function name() { return '<h1 class="cv-name">' + (esc(state.name) || '<span class="cv-empty">Your Name</span>') + '</h1>' + (state.title ? '<div class="cv-role">' + esc(state.title) + '</div>' : ''); }
  function minimalName() {
    var parts = String(state.name || 'Your Name').trim().split(/\s+/);
    var last = parts.length > 1 ? parts.pop() : '';
    return '<h1 class="cv-name">' + esc(parts.join(' ')) + (last ? ' <b>' + esc(last) + '</b>' : '') + '</h1>' + (state.title ? '<div class="cv-role">' + esc(state.title) + '</div>' : '');
  }

  var LAYOUTS = {
    modern: function () {
      return '<aside class="cv-side">' + photoBox() + sec('Contact', contact()) + tags('Skills', state.skills) + tags('Languages', state.languages) + certs() + '</aside>' +
        '<main class="cv-body">' + name() + summary() + experience() + projects() + education() + '</main>';
    },
    executive: function () {
      return '<header class="cv-head">' + photoBox() + name() + contact() + '</header>' + summary() + experience() + projects() + education() + tags('Skills', state.skills) + tags('Languages', state.languages) + certs();
    },
    minimal: function () {
      return '<header class="cv-head"><div>' + minimalName() + contact() + '</div>' + photoBox() + '</header>' + summary() + experience() + projects() + education() + tags('Skills', state.skills) + tags('Languages', state.languages) + certs();
    },
    creative: function () {
      return '<header class="cv-band">' + photoBox() + '<div>' + name() + contact() + '</div></header><div class="cv-cols"><div>' + summary() + experience() + projects() + '</div><div>' + education() + tags('Skills', state.skills) + tags('Languages', state.languages) + certs() + '</div></div>';
    },
    tech: function () {
      return '<header class="cv-head"><div>' + name() + contact() + '</div>' + photoBox() + '</header><div class="cv-content">' + summary() + tags('Tech stack', state.skills) + experience() + projects() + education() + tags('Languages', state.languages) + certs() + '</div>';
    },
    ats: function () {
      return name() + contact() + summary() + tags('Skills', state.skills) + experience() + projects() + education() + tags('Languages', state.languages) + certs();
    }
  };

  function render() {
    var layout = LAYOUTS[state.template] || LAYOUTS.modern;
    paper.className = 'cv-paper tpl-' + (LAYOUTS[state.template] ? state.template : 'modern');
    paper.style.setProperty('--acc', state.accent || '#2563EB');
    paper.innerHTML = layout();
    fit();
  }

  /* ---------- Fit preview to its column ---------- */
  function fit() {
    var wrap = scaleBox.parentElement;
    var avail = wrap.clientWidth - 48;
    var paperW = paper.offsetWidth || 794;
    var s = Math.min(1, avail / paperW);
    scaleBox.style.transform = 'scale(' + s + ')';
    scaleBox.style.height = (paper.offsetHeight * s) + 'px';
    wrap.style.height = (paper.offsetHeight * s + 48) + 'px';
  }
  window.addEventListener('resize', fit);

  /* ---------- Actions ---------- */
  document.getElementById('cvb-sample').addEventListener('click', function () {
    var photo = state.photo;
    state = Object.assign(clone(SAMPLE), { template: state.template, accent: state.accent, photo: photo });
    bindFields(); render(); save();
  });
  document.getElementById('cvb-reset').addEventListener('click', function () {
    if (!confirm('Clear all fields? This cannot be undone.')) return;
    state = clone(EMPTY); bindFields(); syncPicker(); render(); save();
  });
  document.getElementById('cvb-download').addEventListener('click', function () {
    var tip = document.getElementById('cvb-print-tip');
    if (tip) { tip.hidden = false; setTimeout(function () { tip.hidden = true; }, 9000); }
    var prevTitle = document.title;
    document.title = (state.name ? state.name.replace(/[^\w\- ]+/g, '').trim().replace(/\s+/g, '-') : 'My') + '-CV';
    setTimeout(function () { window.print(); document.title = prevTitle; }, 150);
  });

  // Start: show a filled sample on first visit so the preview never looks empty
  var hasData = state.name || state.summary || filled(state.experience, ['role', 'company']).length;
  if (!hasData) state = Object.assign(clone(SAMPLE), { template: state.template, accent: state.accent });
  bindFields(); syncPicker(); render();
})();
