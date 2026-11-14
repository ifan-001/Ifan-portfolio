/* Puzzle games: Queens, Tango, Zip (LinkedIn-style), Memory, 2048. Every puzzle is generated fresh and validated by rules. */
(function () {
  'use strict';
  var root = document.getElementById('game-root');
  if (!root) return;
  var game = root.getAttribute('data-game');
  var statusEl = document.getElementById('game-status');
  var timerEl = document.getElementById('game-timer');
  var newBtn = document.getElementById('game-new');
  var start = 0, tick = null, solved = false;

  function rnd(n) { return Math.floor(Math.random() * n); }
  function shuffle(a) { for (var i = a.length - 1; i > 0; i--) { var j = rnd(i + 1), t = a[i]; a[i] = a[j]; a[j] = t; } return a; }
  function el(tag, cls, html) { var e = document.createElement(tag); if (cls) e.className = cls; if (html != null) e.innerHTML = html; return e; }
  function setStatus(msg, ok) { if (statusEl) { statusEl.textContent = msg; statusEl.classList.toggle('ok', !!ok); } }
  function startTimer() {
    clearInterval(tick); start = Date.now(); solved = false;
    tick = setInterval(function () { if (timerEl && !solved) timerEl.textContent = fmt(Date.now() - start); }, 250);
    if (timerEl) timerEl.textContent = '0:00';
  }
  function fmt(ms) { var s = Math.floor(ms / 1000); return Math.floor(s / 60) + ':' + ('0' + (s % 60)).slice(-2); }
  function win(extra) {
    solved = true; clearInterval(tick);
    var t = fmt(Date.now() - start);
    setStatus('Solved in ' + t + (extra ? ' · ' + extra : '') + ' 🎉', true);
    root.classList.add('is-won');
    try {
      var k = 'game_best_' + game, best = parseInt(localStorage.getItem(k) || '0', 10), ms = Date.now() - start;
      if (!best || ms < best) { localStorage.setItem(k, ms); setStatus('New best time: ' + t + ' 🏆', true); }
    } catch (e) {}
  }

  /* ======================= QUEENS ======================= */
  var REGION_COLORS = ['#FDE68A', '#BFDBFE', '#FBCFE8', '#BBF7D0', '#DDD6FE', '#FED7AA', '#A5F3FC', '#FECACA', '#E5E7EB', '#D9F99D'];
  function queens() {
    var n = 7 + rnd(2); // 7 or 8
    // 1) random valid queen placement: one per row/col, no touching diagonally
    var sol;
    (function place() {
      for (var tries = 0; tries < 500; tries++) {
        var cols = shuffle(Array.from({ length: n }, function (_, i) { return i; }));
        var ok = true;
        for (var r = 1; r < n; r++) if (Math.abs(cols[r] - cols[r - 1]) <= 1) { ok = false; break; }
        if (ok) { sol = cols; return; }
      }
      sol = null;
    })();
    if (!sol) return queens();
    // 2) grow one coloured region from each queen
    var region = Array.from({ length: n }, function () { return Array(n).fill(-1); });
    var frontier = [];
    sol.forEach(function (c, r) { region[r][c] = r; frontier.push([r, c]); });
    var dirs = [[1, 0], [-1, 0], [0, 1], [0, -1]];
    while (frontier.length) {
      var i = rnd(frontier.length), cell = frontier[i], grew = false;
      shuffle(dirs.slice()).forEach(function (d) {
        if (grew) return;
        var rr = cell[0] + d[0], cc = cell[1] + d[1];
        if (rr >= 0 && rr < n && cc >= 0 && cc < n && region[rr][cc] === -1) {
          region[rr][cc] = region[cell[0]][cell[1]]; frontier.push([rr, cc]); grew = true;
        }
      });
      if (!grew) frontier.splice(i, 1);
    }
    var marks = Array.from({ length: n }, function () { return Array(n).fill(0); }); // 0 empty,1 x,2 queen
    root.innerHTML = '';
    var board = el('div', 'gq-board');
    board.style.gridTemplateColumns = 'repeat(' + n + ', 1fr)';
    var cells = [];
    for (var r = 0; r < n; r++) {
      cells.push([]);
      for (var c = 0; c < n; c++) {
        var b = el('button', 'gq-cell');
        b.type = 'button';
        b.style.background = REGION_COLORS[region[r][c] % REGION_COLORS.length];
        b.dataset.r = r; b.dataset.c = c;
        b.setAttribute('aria-label', 'Row ' + (r + 1) + ' column ' + (c + 1));
        if (c < n - 1 && region[r][c] !== region[r][c + 1]) b.classList.add('br');
        if (r < n - 1 && region[r][c] !== region[r + 1][c]) b.classList.add('bb');
        board.appendChild(b); cells[r].push(b);
      }
    }
    board.addEventListener('click', function (e) {
      var b = e.target.closest('.gq-cell'); if (!b || solved) return;
      var r = +b.dataset.r, c = +b.dataset.c;
      marks[r][c] = (marks[r][c] + 1) % 3; draw(); check();
    });
    root.appendChild(board);
    function draw() {
      var bad = conflicts();
      for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) {
        var b = cells[r][c], m = marks[r][c];
        b.textContent = m === 2 ? '♛' : m === 1 ? '×' : '';
        b.classList.toggle('q', m === 2); b.classList.toggle('x', m === 1);
        b.classList.toggle('bad', m === 2 && bad[r + ',' + c]);
      }
    }
    function conflicts() {
      var qs = [], bad = {};
      for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) if (marks[r][c] === 2) qs.push([r, c]);
      for (var i = 0; i < qs.length; i++) for (var j = i + 1; j < qs.length; j++) {
        var a = qs[i], b = qs[j];
        if (a[0] === b[0] || a[1] === b[1] || region[a[0]][a[1]] === region[b[0]][b[1]] || (Math.abs(a[0] - b[0]) <= 1 && Math.abs(a[1] - b[1]) <= 1)) {
          bad[a[0] + ',' + a[1]] = bad[b[0] + ',' + b[1]] = true;
        }
      }
      return bad;
    }
    function check() {
      var qs = 0; for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) if (marks[r][c] === 2) qs++;
      if (qs === n && !Object.keys(conflicts()).length) win();
      else setStatus(qs + ' / ' + n + ' queens placed');
    }
    setStatus('0 / ' + n + ' queens placed'); startTimer();
  }

  /* ======================= TANGO ======================= */
  function tango() {
    var n = 6, SUN = 1, MOON = 2;
    function validPartial(g) {
      for (var i = 0; i < n; i++) {
        var rc = [0, 0, 0], cc = [0, 0, 0];
        for (var j = 0; j < n; j++) {
          rc[g[i][j]]++; cc[g[j][i]]++;
          if (j >= 2) {
            if (g[i][j] && g[i][j] === g[i][j - 1] && g[i][j] === g[i][j - 2]) return false;
            if (g[j][i] && g[j][i] === g[j - 1][i] && g[j][i] === g[j - 2][i]) return false;
          }
        }
        if (rc[1] > 3 || rc[2] > 3 || cc[1] > 3 || cc[2] > 3) return false;
      }
      return true;
    }
    var sol = Array.from({ length: n }, function () { return Array(n).fill(0); });
    (function fill(k) {
      if (k === n * n) return true;
      var r = Math.floor(k / n), c = k % n, opts = shuffle([SUN, MOON]);
      for (var i = 0; i < 2; i++) {
        sol[r][c] = opts[i];
        if (validPartial(sol) && fill(k + 1)) return true;
      }
      sol[r][c] = 0; return false;
    })(0);
    // clues: some given cells + some = / × signs
    var given = Array.from({ length: n }, function () { return Array(n).fill(false); });
    shuffle(Array.from({ length: n * n }, function (_, i) { return i; })).slice(0, 9).forEach(function (k) { given[Math.floor(k / n)][k % n] = true; });
    var signs = [];
    for (var t = 0; t < 10; t++) {
      var r = rnd(n), c = rnd(n), horiz = Math.random() < 0.5;
      var r2 = horiz ? r : r + 1, c2 = horiz ? c + 1 : c;
      if (r2 >= n || c2 >= n) continue;
      signs.push({ r: r, c: c, r2: r2, c2: c2, eq: sol[r][c] === sol[r2][c2] });
    }
    var grid = sol.map(function (row, r) { return row.map(function (v, c) { return given[r][c] ? v : 0; }); });
    root.innerHTML = '';
    var board = el('div', 'gt-board');
    var cells = [];
    for (var rr = 0; rr < n; rr++) {
      cells.push([]);
      for (var cc = 0; cc < n; cc++) {
        var b = el('button', 'gt-cell' + (given[rr][cc] ? ' given' : ''));
        b.type = 'button'; b.dataset.r = rr; b.dataset.c = cc;
        board.appendChild(b); cells[rr].push(b);
      }
    }
    signs.forEach(function (s) {
      var m = el('span', 'gt-sign ' + (s.c2 > s.c ? 'h' : 'v'), s.eq ? '=' : '×');
      m.style.setProperty('--r', s.r); m.style.setProperty('--c', s.c);
      board.appendChild(m);
    });
    board.addEventListener('click', function (e) {
      var b = e.target.closest('.gt-cell'); if (!b || solved || b.classList.contains('given')) return;
      var r = +b.dataset.r, c = +b.dataset.c;
      grid[r][c] = (grid[r][c] + 1) % 3; draw(); check();
    });
    root.appendChild(board);
    function draw() {
      for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) {
        var v = grid[r][c];
        cells[r][c].innerHTML = v === SUN ? '<span class="sun">☀</span>' : v === MOON ? '<span class="moon">☾</span>' : '';
      }
    }
    function check() {
      var full = grid.every(function (row) { return row.every(Boolean); });
      var ok = validPartial(grid) && signs.every(function (s) {
        var a = grid[s.r][s.c], b = grid[s.r2][s.c2];
        return !a || !b || (s.eq ? a === b : a !== b);
      });
      board.classList.toggle('has-error', !ok);
      if (full && ok) win();
      else setStatus(ok ? 'Each row and column: 3 suns, 3 moons, never 3 in a row' : 'A rule is broken — check rows, columns and signs');
    }
    draw(); setStatus('Each row and column: 3 suns, 3 moons, never 3 in a row'); startTimer();
  }

  /* ======================= ZIP ======================= */
  function zip() {
    var n = 6, path = null;
    // Random Hamiltonian path via randomized DFS with Warnsdorff tie-breaking
    for (var attempt = 0; attempt < 400 && !path; attempt++) {
      var seen = Array.from({ length: n }, function () { return Array(n).fill(false); });
      var p = [[rnd(n), rnd(n)]]; seen[p[0][0]][p[0][1]] = true;
      while (p.length < n * n) {
        var cur = p[p.length - 1];
        var nb = neighbors(cur[0], cur[1]).filter(function (q) { return !seen[q[0]][q[1]]; });
        if (!nb.length) break;
        nb = shuffle(nb).sort(function (a, b) { return free(a) - free(b); });
        var nx = nb[0]; seen[nx[0]][nx[1]] = true; p.push(nx);
      }
      if (p.length === n * n) path = p;
      function free(q) { return neighbors(q[0], q[1]).filter(function (z) { return !seen[z[0]][z[1]]; }).length; }
    }
    function neighbors(r, c) { return [[r + 1, c], [r - 1, c], [r, c + 1], [r, c - 1]].filter(function (q) { return q[0] >= 0 && q[0] < n && q[1] >= 0 && q[1] < n; }); }
    if (!path) return zip();
    var numbers = {}, count = 6, idx = [0];
    for (var k = 1; k < count - 1; k++) idx.push(Math.round(k * (n * n - 1) / (count - 1)) + (rnd(3) - 1));
    idx.push(n * n - 1);
    idx.forEach(function (pi, i) { numbers[path[pi][0] + ',' + path[pi][1]] = i + 1; });

    var trail = [];
    root.innerHTML = '';
    var board = el('div', 'gz-board');
    var cells = {};
    for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) {
      var b = el('div', 'gz-cell');
      b.dataset.r = r; b.dataset.c = c;
      if (numbers[r + ',' + c]) b.innerHTML = '<span class="gz-num">' + numbers[r + ',' + c] + '</span>';
      board.appendChild(b); cells[r + ',' + c] = b;
    }
    root.appendChild(board);
    var dragging = false;
    function keyOf(b) { return b.dataset.r + ',' + b.dataset.c; }
    function adjacent(a, b) { var pa = a.split(','), pb = b.split(','); return Math.abs(pa[0] - pb[0]) + Math.abs(pa[1] - pb[1]) === 1; }
    function nextNumber() { var max = 0; trail.forEach(function (k) { if (numbers[k]) max = Math.max(max, numbers[k]); }); return max + 1; }
    function step(b) {
      if (solved || !b) return;
      var k = keyOf(b), pos = trail.indexOf(k);
      if (!trail.length) { if (numbers[k] === 1) trail.push(k); }
      else if (pos === trail.length - 2) trail.pop();               // step back
      else if (pos >= 0) trail = trail.slice(0, pos + 1);             // jump back
      else if (adjacent(trail[trail.length - 1], k)) {
        if (numbers[k] && numbers[k] !== nextNumber()) { flash(b); return; }
        trail.push(k);
      }
      draw();
    }
    function flash(b) { b.classList.add('nope'); setTimeout(function () { b.classList.remove('nope'); }, 300); }
    function draw() {
      Object.keys(cells).forEach(function (k) {
        var b = cells[k], i = trail.indexOf(k);
        b.classList.toggle('on', i >= 0);
        b.classList.toggle('head', i === trail.length - 1 && i >= 0);
        ['u', 'd', 'l', 'r'].forEach(function (d) { b.classList.remove('c-' + d); });
        if (i >= 0) [trail[i - 1], trail[i + 1]].forEach(function (o) {
          if (!o) return;
          var a = k.split(','), p2 = o.split(',');
          b.classList.add('c-' + (p2[0] < a[0] ? 'u' : p2[0] > a[0] ? 'd' : p2[1] < a[1] ? 'l' : 'r'));
        });
      });
      if (trail.length === n * n && numbers[trail[trail.length - 1]] === count) win(n * n + ' cells');
      else setStatus(trail.length ? trail.length + ' / ' + n * n + ' cells · next: ' + nextNumber() : 'Start at 1, drag through every cell, hitting numbers in order');
    }
    function cellAt(e) {
      var pt = e.touches ? e.touches[0] : e;
      var t = document.elementFromPoint(pt.clientX, pt.clientY);
      return t && t.closest ? t.closest('.gz-cell') : null;
    }
    board.addEventListener('mousedown', function (e) { dragging = true; step(cellAt(e)); });
    board.addEventListener('mousemove', function (e) { if (dragging) { var b = cellAt(e); if (b && keyOf(b) !== trail[trail.length - 1]) step(b); } });
    window.addEventListener('mouseup', function () { dragging = false; });
    board.addEventListener('touchstart', function (e) { dragging = true; step(cellAt(e)); e.preventDefault(); }, { passive: false });
    board.addEventListener('touchmove', function (e) { var b = cellAt(e); if (b && keyOf(b) !== trail[trail.length - 1]) step(b); e.preventDefault(); }, { passive: false });
    board.addEventListener('touchend', function () { dragging = false; });
    var undo = document.getElementById('game-undo');
    if (undo) { undo.hidden = false; undo.onclick = function () { if (!solved) { trail.pop(); draw(); } }; }
    draw(); startTimer();
  }

  /* ======================= MEMORY ======================= */
  function memory() {
    var icons = ['PHP', 'JS', 'SQL', 'Git', 'API', 'CSS', 'AI', 'Dart'];
    var deck = shuffle(icons.concat(icons)), open = [], matched = 0, moves = 0, lock = false;
    root.innerHTML = '';
    var board = el('div', 'gm-board');
    deck.forEach(function (v, i) {
      var b = el('button', 'gm-card', '<span class="gm-back">&lt;/&gt;</span><span class="gm-front">' + v + '</span>');
      b.type = 'button'; b.dataset.v = v; b.setAttribute('aria-label', 'Card ' + (i + 1));
      board.appendChild(b);
    });
    board.addEventListener('click', function (e) {
      var b = e.target.closest('.gm-card');
      if (!b || lock || b.classList.contains('flip') || solved) return;
      b.classList.add('flip'); open.push(b);
      if (open.length === 2) {
        moves++; lock = true;
        if (open[0].dataset.v === open[1].dataset.v) {
          open.forEach(function (c) { c.classList.add('done'); }); open = []; lock = false; matched++;
          if (matched === icons.length) win(moves + ' moves');
        } else {
          setTimeout(function () { open.forEach(function (c) { c.classList.remove('flip'); }); open = []; lock = false; }, 750);
        }
        if (!solved) setStatus(moves + ' moves · ' + matched + ' / ' + icons.length + ' pairs');
      }
    });
    root.appendChild(board); setStatus('Find all ' + icons.length + ' pairs'); startTimer();
  }

  /* ======================= 2048 ======================= */
  function g2048() {
    var n = 4, grid, score = 0, over = false;
    root.innerHTML = '';
    var board = el('div', 'g2-board');
    root.appendChild(board);
    function empty() { var e = []; for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) if (!grid[r][c]) e.push([r, c]); return e; }
    function add() { var e = empty(); if (!e.length) return; var p = e[rnd(e.length)]; grid[p[0]][p[1]] = Math.random() < 0.9 ? 2 : 4; }
    function draw() {
      board.innerHTML = '';
      for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) {
        var v = grid[r][c];
        board.appendChild(el('div', 'g2-tile v' + Math.min(v, 4096), v ? String(v) : ''));
      }
      if (!solved) setStatus('Score ' + score + (over ? ' · No moves left — press New game' : ''));
    }
    function slide(row) {
      var a = row.filter(Boolean);
      for (var i = 0; i < a.length - 1; i++) if (a[i] === a[i + 1]) { a[i] *= 2; score += a[i]; if (a[i] === 2048 && !solved) win('Score ' + score); a.splice(i + 1, 1); }
      while (a.length < n) a.push(0);
      return a;
    }
    function move(dir) {
      if (over) return;
      var before = JSON.stringify(grid);
      for (var i = 0; i < n; i++) {
        var line = [];
        for (var j = 0; j < n; j++) line.push(dir === 'left' ? grid[i][j] : dir === 'right' ? grid[i][n - 1 - j] : dir === 'up' ? grid[j][i] : grid[n - 1 - j][i]);
        line = slide(line);
        for (var k = 0; k < n; k++) {
          if (dir === 'left') grid[i][k] = line[k]; else if (dir === 'right') grid[i][n - 1 - k] = line[k];
          else if (dir === 'up') grid[k][i] = line[k]; else grid[n - 1 - k][i] = line[k];
        }
      }
      if (JSON.stringify(grid) !== before) add();
      over = !empty().length && !canMerge();
      draw();
    }
    function canMerge() { for (var r = 0; r < n; r++) for (var c = 0; c < n; c++) { if (c < n - 1 && grid[r][c] === grid[r][c + 1]) return true; if (r < n - 1 && grid[r][c] === grid[r + 1][c]) return true; } return false; }
    grid = Array.from({ length: n }, function () { return Array(n).fill(0); });
    add(); add(); draw(); startTimer();
    var keyMap = { ArrowLeft: 'left', ArrowRight: 'right', ArrowUp: 'up', ArrowDown: 'down', a: 'left', d: 'right', w: 'up', s: 'down' };
    document.onkeydown = function (e) { if (keyMap[e.key]) { e.preventDefault(); move(keyMap[e.key]); } };
    var sx, sy;
    board.addEventListener('touchstart', function (e) { sx = e.touches[0].clientX; sy = e.touches[0].clientY; }, { passive: true });
    board.addEventListener('touchend', function (e) {
      var dx = e.changedTouches[0].clientX - sx, dy = e.changedTouches[0].clientY - sy;
      if (Math.max(Math.abs(dx), Math.abs(dy)) < 30) return;
      move(Math.abs(dx) > Math.abs(dy) ? (dx > 0 ? 'right' : 'left') : (dy > 0 ? 'down' : 'up'));
    });
  }

  var GAMES = { queens: queens, tango: tango, zip: zip, memory: memory, '2048': g2048 };
  function newGame() { root.classList.remove('is-won'); (GAMES[game] || queens)(); }
  if (newBtn) newBtn.addEventListener('click', newGame);
  newGame();
})();
