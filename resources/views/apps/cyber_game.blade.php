@extends('layouts.app')

@section('title', 'Play Code Breaker: Cyber Defense Arcade Game | Hasnain Al Ifan')
@section('meta_description', 'Play Code Breaker: Cyber Defense - a retro terminal arcade game built directly with HTML5 Canvas.')

@section('content')
<div class="cyber-game-page" style="min-height:100vh; padding:90px 20px 40px 20px; max-width:900px; margin:0 auto; text-align:center;">
    
    {{-- Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('apps.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; text-decoration:none;">
            <span>&larr;</span>
            <span>Back to Tools Hub</span>
        </a>

        <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:0.85rem; background:rgba(168,85,247,0.15); color:#A855F7; padding:4px 12px; border-radius:12px; font-weight:700;">
                Arcade HTML5 Canvas Game
            </span>
        </div>
    </div>

    {{-- Game Screen Card --}}
    <div style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:20px; box-shadow:0 10px 30px rgba(0,0,0,0.2); margin-bottom:24px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
            <h1 style="font-size:1.25rem; font-weight:800; color:var(--text-primary); margin:0; display:flex; align-items:center; gap:8px;">
                <span>🎮 Code Breaker: Cyber Defense</span>
            </h1>
            <div style="font-size:0.85rem; color:var(--text-secondary); display:flex; gap:16px;">
                <span>High Score: <strong id="game-highscore" style="color:var(--accent-blue);">0</strong></span>
                <span>Score: <strong id="game-score" style="color:#10B981;">0</strong></span>
                <span>Lives: <strong id="game-lives" style="color:#EF4444;">❤️❤️❤️</strong></span>
            </div>
        </div>

        {{-- Canvas Container --}}
        <div style="position:relative; width:100%; max-width:700px; margin:0 auto; background:#0B1120; border:2px solid var(--accent-blue); border-radius:10px; overflow:hidden; aspect-ratio:4/3; box-shadow:inset 0 0 20px rgba(59,130,246,0.3);">
            <canvas id="gameCanvas" width="700" height="525" style="width:100%; height:100%; display:block;"></canvas>

            {{-- Game Overlay (Start / Game Over) --}}
            <div id="game-overlay" style="position:absolute; inset:0; background:rgba(11,17,32,0.85); backdrop-filter:blur(4px); display:flex; flex-direction:column; align-items:center; justify-content:center; padding:20px;">
                <div style="font-size:2.5rem; margin-bottom:8px;">🛡️</div>
                <h2 id="overlay-title" style="font-size:1.6rem; font-weight:800; color:#fff; margin:0 0 8px 0;">DEFEND THE CORE</h2>
                <p id="overlay-desc" style="color:#94A3B8; font-size:0.9rem; max-width:400px; margin:0 0 20px 0; line-height:1.5;">
                    Bugs and DDoS packets are attacking the production server! Fire security patches to neutralize them.
                </p>
                <button type="button" onclick="startGame()" id="btn-start-game" class="btn btn-primary" style="padding:12px 28px; font-weight:700; font-size:1rem; border-radius:24px;">
                    Start Game
                </button>
            </div>
        </div>

        {{-- Mobile & Touch Controls (visible on touch devices or small screens) --}}
        <div class="game-touch-controls" style="display:flex; justify-content:center; gap:16px; margin-top:16px;">
            <button type="button" class="btn btn-secondary touch-btn" id="btn-touch-left" style="flex:1; max-width:140px; padding:14px; font-size:1.2rem; font-weight:bold; user-select:none;">
                ◀ LEFT
            </button>
            <button type="button" class="btn btn-primary touch-btn" id="btn-touch-fire" style="flex:1.5; max-width:180px; padding:14px; font-size:1.1rem; font-weight:bold; user-select:none; background:#EF4444; border-color:#EF4444;">
                🔥 FIRE PATCH
            </button>
            <button type="button" class="btn btn-secondary touch-btn" id="btn-touch-right" style="flex:1; max-width:140px; padding:14px; font-size:1.2rem; font-weight:bold; user-select:none;">
                RIGHT ▶
            </button>
        </div>

        <div style="font-size:0.8rem; color:var(--text-secondary); margin-top:12px;">
            Keyboard: <strong>&larr; / &rarr;</strong> or <strong>A / D</strong> to Move &bull; <strong>SPACE</strong> to Fire
        </div>
    </div>
</div>

<script>
const canvas = document.getElementById('gameCanvas');
const ctx = canvas.getContext('2d');
const overlay = document.getElementById('game-overlay');
const overlayTitle = document.getElementById('overlay-title');
const overlayDesc = document.getElementById('overlay-desc');
const scoreEl = document.getElementById('game-score');
const livesEl = document.getElementById('game-lives');
const highscoreEl = document.getElementById('game-highscore');

let gameRunning = false;
let score = 0;
let lives = 3;
let highScore = parseInt(localStorage.getItem('cyber_defense_highscore') || '0');
highscoreEl.textContent = highScore;

// Audio synthesis
const AudioContextClass = window.AudioContext || window.webkitAudioContext;
let audioCtx = null;

function playSound(freq, duration, type = 'sine') {
    if (!audioCtx) {
        try { audioCtx = new AudioContextClass(); } catch(e) {}
    }
    if (!audioCtx) return;
    try {
        if (audioCtx.state === 'suspended') audioCtx.resume();
        const osc = audioCtx.createOscillator();
        const gain = audioCtx.createGain();
        osc.type = type;
        osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
        gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + duration);
        osc.connect(gain);
        gain.connect(audioCtx.destination);
        osc.start();
        osc.stop(audioCtx.currentTime + duration);
    } catch(e) {}
}

// Game State Objects
const player = {
    x: 350,
    y: 480,
    width: 44,
    height: 24,
    speed: 7,
    movingLeft: false,
    movingRight: false,
};

let bullets = [];
let enemies = [];
let particles = [];
let spawnTimer = 0;
let animationId = null;

function startGame() {
    overlay.style.display = 'none';
    score = 0;
    lives = 3;
    bullets = [];
    enemies = [];
    particles = [];
    player.x = canvas.width / 2 - player.width / 2;
    scoreEl.textContent = score;
    updateLivesUI();
    gameRunning = true;
    lastTime = performance.now();
    cancelAnimationFrame(animationId);
    gameLoop();
}

function updateLivesUI() {
    livesEl.textContent = '❤️'.repeat(Math.max(0, lives));
}

function gameOver() {
    gameRunning = false;
    cancelAnimationFrame(animationId);
    playSound(150, 0.6, 'sawtooth');

    if (score > highScore) {
        highScore = score;
        localStorage.setItem('cyber_defense_highscore', highScore);
        highscoreEl.textContent = highScore;
    }

    overlayTitle.textContent = 'SYSTEM BREACHED!';
    overlayDesc.textContent = `Production defense failed. Final Score: ${score} points. High Score: ${highScore}`;
    document.getElementById('btn-start-game').textContent = 'Play Again';
    overlay.style.display = 'flex';
}

function shoot() {
    if (!gameRunning) return;
    bullets.push({
        x: player.x + player.width / 2 - 3,
        y: player.y - 8,
        width: 6,
        height: 14,
        speed: 10
    });
    playSound(600, 0.1, 'square');
}

// Controls
window.addEventListener('keydown', (e) => {
    if (e.code === 'ArrowLeft' || e.code === 'KeyA') player.movingLeft = true;
    if (e.code === 'ArrowRight' || e.code === 'KeyD') player.movingRight = true;
    if (e.code === 'Space') {
        e.preventDefault();
        shoot();
    }
});

window.addEventListener('keyup', (e) => {
    if (e.code === 'ArrowLeft' || e.code === 'KeyA') player.movingLeft = false;
    if (e.code === 'ArrowRight' || e.code === 'KeyD') player.movingRight = false;
});

// Touch controls for Samsung Galaxy A54
const leftBtn = document.getElementById('btn-touch-left');
const rightBtn = document.getElementById('btn-touch-right');
const fireBtn = document.getElementById('btn-touch-fire');

leftBtn.addEventListener('touchstart', (e) => { e.preventDefault(); player.movingLeft = true; });
leftBtn.addEventListener('touchend', (e) => { e.preventDefault(); player.movingLeft = false; });
leftBtn.addEventListener('mousedown', () => player.movingLeft = true);
leftBtn.addEventListener('mouseup', () => player.movingLeft = false);

rightBtn.addEventListener('touchstart', (e) => { e.preventDefault(); player.movingRight = true; });
rightBtn.addEventListener('touchend', (e) => { e.preventDefault(); player.movingRight = false; });
rightBtn.addEventListener('mousedown', () => player.movingRight = true);
rightBtn.addEventListener('mouseup', () => player.movingRight = false);

fireBtn.addEventListener('touchstart', (e) => { e.preventDefault(); shoot(); });
fireBtn.addEventListener('click', () => shoot());

function gameLoop() {
    if (!gameRunning) return;

    // Clear Screen
    ctx.fillStyle = '#0B1120';
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    // Starfield grid
    ctx.fillStyle = 'rgba(59,130,246,0.1)';
    for (let i = 0; i < 20; i++) {
        ctx.fillRect((i * 37 + (Date.now() / 20)) % canvas.width, (i * 29) % canvas.height, 2, 2);
    }

    // Move Player
    if (player.movingLeft && player.x > 0) player.x -= player.speed;
    if (player.movingRight && player.x < canvas.width - player.width) player.x += player.speed;

    // Draw Player Defender
    ctx.fillStyle = '#3B82F6';
    ctx.beginPath();
    ctx.moveTo(player.x + player.width / 2, player.y);
    ctx.lineTo(player.x + player.width, player.y + player.height);
    ctx.lineTo(player.x, player.y + player.height);
    ctx.closePath();
    ctx.fill();
    ctx.fillStyle = '#60A5FA';
    ctx.fillRect(player.x + player.width / 2 - 2, player.y - 4, 4, 8);

    // Update & Draw Bullets
    ctx.fillStyle = '#10B981';
    for (let i = bullets.length - 1; i >= 0; i--) {
        const b = bullets[i];
        b.y -= b.speed;
        ctx.fillRect(b.x, b.y, b.width, b.height);
        if (b.y < -10) bullets.splice(i, 1);
    }

    // Spawn Enemies
    spawnTimer++;
    if (spawnTimer > 40) {
        spawnTimer = 0;
        const types = ['bug', 'ddos', 'glitch'];
        const type = types[Math.floor(Math.random() * types.length)];
        enemies.push({
            x: Math.random() * (canvas.width - 40),
            y: -30,
            width: 32,
            height: 26,
            speed: 2 + Math.random() * 2,
            type: type
        });
    }

    // Update & Draw Enemies
    for (let i = enemies.length - 1; i >= 0; i--) {
        const enemy = enemies[i];
        enemy.y += enemy.speed;

        // Draw Enemy
        ctx.fillStyle = enemy.type === 'bug' ? '#EF4444' : (enemy.type === 'ddos' ? '#F59E0B' : '#EC4899');
        ctx.fillRect(enemy.x, enemy.y, enemy.width, enemy.height);
        ctx.fillStyle = '#ffffff';
        ctx.font = '10px monospace';
        ctx.fillText(enemy.type === 'bug' ? 'BUG' : (enemy.type === 'ddos' ? 'DDOS' : 'SYN'), enemy.x + 4, enemy.y + 16);

        // Check Collision with Bullets
        for (let j = bullets.length - 1; j >= 0; j--) {
            const b = bullets[j];
            if (b.x < enemy.x + enemy.width && b.x + b.width > enemy.x &&
                b.y < enemy.y + enemy.height && b.y + b.height > enemy.y) {
                
                // Boom
                playSound(320, 0.15, 'triangle');
                score += 10;
                scoreEl.textContent = score;

                // Add particles
                for (let p = 0; p < 8; p++) {
                    particles.push({
                        x: enemy.x + enemy.width / 2,
                        y: enemy.y + enemy.height / 2,
                        vx: (Math.random() - 0.5) * 6,
                        vy: (Math.random() - 0.5) * 6,
                        life: 20,
                        color: ctx.fillStyle
                    });
                }

                enemies.splice(i, 1);
                bullets.splice(j, 1);
                break;
            }
        }

        // Check Reached Bottom (Server Breached)
        if (enemy && enemy.y > canvas.height) {
            enemies.splice(i, 1);
            lives--;
            updateLivesUI();
            playSound(200, 0.25, 'sawtooth');
            if (lives <= 0) {
                gameOver();
                return;
            }
        }
    }

    // Update & Draw Particles
    for (let i = particles.length - 1; i >= 0; i--) {
        const p = particles[i];
        p.x += p.vx;
        p.y += p.vy;
        p.life--;
        ctx.fillStyle = p.color;
        ctx.fillRect(p.x, p.y, 3, 3);
        if (p.life <= 0) particles.splice(i, 1);
    }

    animationId = requestAnimationFrame(gameLoop);
}
</script>
@endsection
