/**
 * EDUPLEX · CINEMATIC ENGINE v3
 * World-class CGI animations — NO glitching, NO login bugs
 *
 * SCENES:
 *  1. NEBULA BACKGROUND   — deep space particle field, aurora waves, star clusters
 *  2. VOID IMPLOSION       — role card click: universe collapses into singularity
 *  3. BIG BANG BIRTH       — login page: explodes from singularity with shockwaves
 *  4. PIXEL SHATTER        — login submit: card shatters like glass, form submits
 *  5. CONSTELLATION ASSEMBLE — dashboard: stars trace lines then form the UI
 *  6. PAGE WARP            — inner nav: space-warp speed travel between pages
 */

(function () {
'use strict';

/* ─── Canvas layer ─────────────────────────────────── */
const CV = document.createElement('canvas');
CV.style.cssText = 'position:fixed;inset:0;width:100%;height:100%;z-index:0;pointer-events:none;display:block;';
document.body.insertBefore(CV, document.body.firstChild);
const X = CV.getContext('2d');
let W = 0, H = 0;

function resize() {
  W = CV.width  = window.innerWidth;
  H = CV.height = window.innerHeight;
}
resize();
window.addEventListener('resize', resize, { passive: true });

/* ─── Math helpers ─────────────────────────────────── */
const PI2 = Math.PI * 2;
const lerp  = (a, b, t) => a + (b - a) * t;
const clamp = (v, lo, hi) => Math.max(lo, Math.min(hi, v));
const rand  = (lo, hi) => lo + Math.random() * (hi - lo);
const randInt = (lo, hi) => Math.floor(rand(lo, hi + 1));

/* ─── Time ─────────────────────────────────────────── */
let T = 0; // global time counter

/* ════════════════════════════════════════════════════
   SCENE 1 — NEBULA BACKGROUND (always running)
   ════════════════════════════════════════════════════ */

/* Star layers — parallax depth */
const STARS = Array.from({ length: 180 }, () => ({
  x: rand(0, 1), y: rand(0, 1),
  r: rand(0.2, 1.8),
  twinkle: rand(0, PI2),
  speed: rand(0.0003, 0.001),
  layer: randInt(0, 2),           // 0=far, 1=mid, 2=close
  col: Math.random() > 0.85
    ? `hsl(${randInt(200, 240)},80%,85%)`
    : `hsl(${randInt(210,230)},60%,${randInt(75,100)}%)`
}));

/* Nebula cloud nodes (soft glowing blobs) */
const NEBULAE = Array.from({ length: 6 }, () => ({
  x: rand(0.1, 0.9), y: rand(0.1, 0.9),
  r: rand(100, 260),
  hue: randInt(200, 260),
  sat: randInt(60, 100),
  alpha: rand(0.03, 0.08),
  drift: rand(0.00005, 0.00015),
  phase: rand(0, PI2)
}));

/* Shooting stars */
const SHOOTS = [];
function maybeShoot() {
  if (Math.random() < 0.003) {
    SHOOTS.push({
      x: rand(0.1, 0.9) * W,
      y: rand(0, 0.4) * H,
      vx: rand(6, 14),
      vy: rand(2, 6),
      life: 1,
      len: rand(80, 180)
    });
  }
}

/* Aurora wave bands */
const AURORAS = Array.from({ length: 3 }, (_, i) => ({
  hue: [210, 180, 270][i],
  phase: (i / 3) * PI2,
  amp: rand(30, 80),
  freq: rand(0.003, 0.006),
  speed: rand(0.008, 0.015),
  y: rand(0.15, 0.55),
  alpha: rand(0.04, 0.09)
}));

function drawNebula() {
  /* Deep space gradient base */
  const bg = X.createRadialGradient(W * 0.5, H * 0.4, 0, W * 0.5, H * 0.5, W * 0.8);
  bg.addColorStop(0,   'rgba(8,14,35,0)');
  bg.addColorStop(0.5, 'rgba(4,8,22,0)');
  bg.addColorStop(1,   'rgba(2,4,14,0)');
  // Don't fill with bg — let CSS handle the dark bg, canvas is additive

  /* Nebula blobs */
  for (const n of NEBULAE) {
    n.phase += n.drift;
    const px = (n.x + Math.sin(n.phase) * 0.06) * W;
    const py = (n.y + Math.cos(n.phase * 0.7) * 0.04) * H;
    const pulse = 1 + Math.sin(n.phase * 3) * 0.15;
    const grad = X.createRadialGradient(px, py, 0, px, py, n.r * pulse);
    grad.addColorStop(0,   `hsla(${n.hue},${n.sat}%,60%,${n.alpha * 1.5})`);
    grad.addColorStop(0.4, `hsla(${n.hue},${n.sat}%,50%,${n.alpha})`);
    grad.addColorStop(1,   `hsla(${n.hue},${n.sat}%,40%,0)`);
    X.beginPath();
    X.arc(px, py, n.r * pulse, 0, PI2);
    X.fillStyle = grad;
    X.fill();
  }

  /* Aurora waves */
  for (const a of AURORAS) {
    a.phase += a.speed * 0.016;
    X.beginPath();
    X.moveTo(0, a.y * H);
    for (let xi = 0; xi <= W; xi += 4) {
      const yi = a.y * H + Math.sin(xi * a.freq + a.phase) * a.amp
                         + Math.sin(xi * a.freq * 2.3 + a.phase * 1.7) * a.amp * 0.4;
      X.lineTo(xi, yi);
    }
    X.lineTo(W, H); X.lineTo(0, H); X.closePath();
    const ag = X.createLinearGradient(0, a.y * H - a.amp, 0, a.y * H + a.amp);
    ag.addColorStop(0,   `hsla(${a.hue},80%,65%,0)`);
    ag.addColorStop(0.5, `hsla(${a.hue},80%,65%,${a.alpha})`);
    ag.addColorStop(1,   `hsla(${a.hue},80%,65%,0)`);
    X.fillStyle = ag;
    X.fill();
  }

  /* Stars */
  for (const s of STARS) {
    s.twinkle += s.speed;
    const tw = 0.4 + 0.6 * Math.abs(Math.sin(s.twinkle));
    const px = s.x * W;
    const py = s.y * H;
    const r  = s.r * tw;

    /* Glow */
    if (r > 1) {
      const gd = X.createRadialGradient(px, py, 0, px, py, r * 4);
      gd.addColorStop(0, s.col.replace(')', `,${tw * 0.4})`).replace('hsl', 'hsla'));
      gd.addColorStop(1, 'rgba(0,0,0,0)');
      X.beginPath(); X.arc(px, py, r * 4, 0, PI2);
      X.fillStyle = gd; X.fill();
    }

    /* Core */
    X.beginPath(); X.arc(px, py, r, 0, PI2);
    X.fillStyle = s.col; X.globalAlpha = tw; X.fill();
    X.globalAlpha = 1;
  }

  /* Shooting stars */
  maybeShoot();
  for (let i = SHOOTS.length - 1; i >= 0; i--) {
    const s = SHOOTS[i];
    s.x += s.vx; s.y += s.vy; s.life -= 0.025;
    if (s.life <= 0) { SHOOTS.splice(i, 1); continue; }
    const tail = X.createLinearGradient(s.x - s.vx * (s.len / s.vx), s.y - s.vy * (s.len / s.vx), s.x, s.y);
    tail.addColorStop(0, 'rgba(255,255,255,0)');
    tail.addColorStop(1, `rgba(180,220,255,${s.life * 0.9})`);
    X.beginPath();
    X.moveTo(s.x - s.vx * (s.len / s.vx), s.y - s.vy * (s.len / s.vx));
    X.lineTo(s.x, s.y);
    X.strokeStyle = tail; X.lineWidth = 1.5 * s.life; X.stroke();
  }
}

/* ════════════════════════════════════════════════════
   SCENE 2 — VOID IMPLOSION
   When a role card is clicked: everything spirals into singularity
   ════════════════════════════════════════════════════ */

let voidActive = false;
let voidProgress = 0;
let voidCX = 0, voidCY = 0;
let onVoidComplete = null;

/* Vortex particles */
let vortexPts = [];

function startVoidImplosion(cx, cy, cb) {
  voidActive = true;
  voidProgress = 0;
  voidCX = cx; voidCY = cy;
  onVoidComplete = cb;
  vortexPts = Array.from({ length: 200 }, () => {
    const angle = rand(0, PI2);
    const dist  = rand(100, Math.max(W, H) * 0.7);
    return {
      angle,
      dist,
      baseDist: dist,
      speed: rand(0.04, 0.12),
      r: rand(1, 4),
      hue: randInt(180, 260),
      alpha: rand(0.6, 1)
    };
  });
}

function drawVoidImplosion(dt) {
  if (!voidActive) return;
  voidProgress += dt * 0.9;

  const t = clamp(voidProgress, 0, 1);
  const ease = t * t * (3 - 2 * t); // smooth step

  /* Darken screen */
  X.fillStyle = `rgba(0,0,10,${ease * 0.85})`;
  X.fillRect(0, 0, W, H);

  /* Vortex particles spiraling in */
  for (const p of vortexPts) {
    p.angle += p.speed * (1 + ease * 6);
    p.dist   = p.baseDist * (1 - ease * 0.98);

    if (p.dist < 3) continue;

    const px = voidCX + Math.cos(p.angle) * p.dist;
    const py = voidCY + Math.sin(p.angle) * p.dist;
    const glow = X.createRadialGradient(px, py, 0, px, py, p.r * 3);
    glow.addColorStop(0, `hsla(${p.hue},100%,80%,${p.alpha})`);
    glow.addColorStop(1, 'rgba(0,0,0,0)');
    X.beginPath(); X.arc(px, py, p.r * 3, 0, PI2);
    X.fillStyle = glow; X.fill();
    X.beginPath(); X.arc(px, py, p.r * (1 - ease * 0.5), 0, PI2);
    X.fillStyle = `hsla(${p.hue},100%,90%,${p.alpha * (1 - ease * 0.5)})`;
    X.fill();
  }

  /* Event horizon ring */
  const ringR = (1 - ease) * 200 + 4;
  const ringGrad = X.createRadialGradient(voidCX, voidCY, ringR * 0.7, voidCX, voidCY, ringR);
  ringGrad.addColorStop(0, `rgba(22,119,255,${ease * 0.8})`);
  ringGrad.addColorStop(0.5, `rgba(0,212,255,${ease * 0.5})`);
  ringGrad.addColorStop(1, 'rgba(0,0,0,0)');
  X.beginPath(); X.arc(voidCX, voidCY, ringR, 0, PI2);
  X.strokeStyle = `rgba(100,200,255,${ease})`;
  X.lineWidth = 3 + ease * 8;
  X.stroke();

  /* Singularity core */
  if (ease > 0.5) {
    const coreA = (ease - 0.5) * 2;
    const coreGrad = X.createRadialGradient(voidCX, voidCY, 0, voidCX, voidCY, 60 * coreA);
    coreGrad.addColorStop(0, `rgba(255,255,255,${coreA})`);
    coreGrad.addColorStop(0.2, `rgba(100,180,255,${coreA * 0.8})`);
    coreGrad.addColorStop(1, 'rgba(0,0,0,0)');
    X.beginPath(); X.arc(voidCX, voidCY, 60 * coreA, 0, PI2);
    X.fillStyle = coreGrad; X.fill();
  }

  if (t >= 1) {
    voidActive = false;
    if (onVoidComplete) { onVoidComplete(); onVoidComplete = null; }
  }
}

/* ════════════════════════════════════════════════════
   SCENE 3 — BIG BANG BIRTH
   Login pages enter with explosion from singularity
   ════════════════════════════════════════════════════ */

let bangActive = false;
let bangProgress = 0;
let bangDebris = [];
let bangRings  = [];

function startBigBang() {
  bangActive   = true;
  bangProgress = 0;
  const cx = W / 2, cy = H / 2;

  bangDebris = Array.from({ length: 160 }, () => {
    const angle = rand(0, PI2);
    const speed = rand(3, 18);
    return {
      x: cx, y: cy,
      vx: Math.cos(angle) * speed,
      vy: Math.sin(angle) * speed,
      r: rand(1.5, 5),
      hue: randInt(180, 280),
      life: 1,
      decay: rand(0.008, 0.018),
      trail: []
    };
  });

  bangRings = [0, 0.08, 0.16, 0.24].map((delay, i) => ({
    delay,
    hue: [210, 180, 240, 200][i],
    maxR: rand(400, 700),
    lineW: 4 - i * 0.5
  }));
}

function drawBigBang(dt) {
  if (!bangActive) return;
  bangProgress += dt * 0.7;
  const t = bangProgress;

  /* Initial flash */
  if (t < 0.15) {
    const flashA = Math.max(0, 1 - t / 0.15);
    X.fillStyle = `rgba(200,230,255,${flashA * 0.7})`;
    X.fillRect(0, 0, W, H);
  }

  /* Shockwave rings */
  for (const ring of bangRings) {
    const rt = Math.max(0, t - ring.delay);
    if (rt <= 0) continue;
    const r  = rt * ring.maxR;
    const a  = Math.max(0, 1 - rt * 1.4);
    X.beginPath();
    X.arc(W / 2, H / 2, r, 0, PI2);
    X.strokeStyle = `hsla(${ring.hue},90%,70%,${a})`;
    X.lineWidth = ring.lineW * (1 - rt * 0.6);
    X.stroke();

    /* Inner glow for first ring */
    if (ring.delay === 0 && a > 0.1) {
      const gr = X.createRadialGradient(W/2,H/2, 0, W/2,H/2, r);
      gr.addColorStop(0, 'rgba(0,0,0,0)');
      gr.addColorStop(0.85, 'rgba(0,0,0,0)');
      gr.addColorStop(0.95, `hsla(${ring.hue},90%,70%,${a * 0.15})`);
      gr.addColorStop(1, 'rgba(0,0,0,0)');
      X.beginPath(); X.arc(W/2, H/2, r, 0, PI2);
      X.fillStyle = gr; X.fill();
    }
  }

  /* Debris particles */
  for (const d of bangDebris) {
    if (d.life <= 0) continue;
    d.trail.push({ x: d.x, y: d.y });
    if (d.trail.length > 8) d.trail.shift();
    d.vx *= 0.97; d.vy *= 0.97;
    d.x += d.vx; d.y += d.vy;
    d.life -= d.decay;

    /* Trail */
    if (d.trail.length > 1) {
      for (let i = 1; i < d.trail.length; i++) {
        const ta = (i / d.trail.length) * d.life * 0.6;
        X.beginPath();
        X.moveTo(d.trail[i-1].x, d.trail[i-1].y);
        X.lineTo(d.trail[i].x, d.trail[i].y);
        X.strokeStyle = `hsla(${d.hue},100%,75%,${ta})`;
        X.lineWidth = d.r * 0.5 * d.life;
        X.stroke();
      }
    }

    /* Core dot */
    const gd = X.createRadialGradient(d.x, d.y, 0, d.x, d.y, d.r * 2.5);
    gd.addColorStop(0, `hsla(${d.hue},100%,95%,${d.life})`);
    gd.addColorStop(0.5, `hsla(${d.hue},100%,70%,${d.life * 0.6})`);
    gd.addColorStop(1, 'rgba(0,0,0,0)');
    X.beginPath(); X.arc(d.x, d.y, d.r * 2.5, 0, PI2);
    X.fillStyle = gd; X.fill();
  }

  if (t > 2) { bangActive = false; }
}

/* ════════════════════════════════════════════════════
   SCENE 4 — GLASS SHATTER (login submit)
   Card breaks into shards, form submits RELIABLY
   ════════════════════════════════════════════════════ */

let shatterActive   = false;
let shatterProgress = 0;
let shatterShards   = [];
let shatterCallback = null;
let shatterSubmitted = false;

function startShatter(el, cb) {
  if (shatterActive) return;
  shatterActive    = true;
  shatterProgress  = 0;
  shatterCallback  = cb;
  shatterSubmitted = false;

  const rect = el.getBoundingClientRect();
  const cx = rect.left + rect.width  / 2;
  const cy = rect.top  + rect.height / 2;

  /* Generate Voronoi-like shards from box bounds */
  const seeds = Array.from({ length: 40 }, () => ({
    x: rect.left + Math.random() * rect.width,
    y: rect.top  + Math.random() * rect.height
  }));

  shatterShards = seeds.map(s => {
    const angle = Math.atan2(s.y - cy, s.x - cx);
    const dist  = Math.hypot(s.x - cx, s.y - cy);
    const speed = rand(2, 10);
    return {
      x: s.x, y: s.y,
      vx: Math.cos(angle) * speed * (0.5 + dist / 200),
      vy: Math.sin(angle) * speed * (0.5 + dist / 200) - rand(0, 3),
      rot: 0,
      rotV: (Math.random() - 0.5) * 0.2,
      w: rand(20, 60),
      h: rand(15, 40),
      hue: randInt(200, 250),
      alpha: rand(0.6, 0.95),
      life: 1,
      decay: rand(0.012, 0.02),
      gravity: rand(0.1, 0.25)
    };
  });
}

function drawShatter(dt) {
  if (!shatterActive) return;
  shatterProgress += dt;

  /* Submit form halfway through animation — reliable timing */
  if (!shatterSubmitted && shatterProgress > 0.4) {
    shatterSubmitted = true;
    if (shatterCallback) shatterCallback();
  }

  let anyAlive = false;
  for (const s of shatterShards) {
    if (s.life <= 0) continue;
    anyAlive = true;
    s.vx *= 0.97; s.vy = s.vy * 0.97 + s.gravity;
    s.x  += s.vx; s.y  += s.vy;
    s.rot += s.rotV; s.life -= s.decay;

    X.save();
    X.translate(s.x, s.y);
    X.rotate(s.rot);
    X.globalAlpha = s.life;

    /* Shard face */
    X.fillStyle = `hsla(${s.hue},60%,${30 + s.life * 20}%,${s.alpha})`;
    X.fillRect(-s.w/2, -s.h/2, s.w, s.h);

    /* Shard edge highlight */
    X.strokeStyle = `hsla(${s.hue},80%,80%,${s.life * 0.8})`;
    X.lineWidth = 0.8;
    X.strokeRect(-s.w/2, -s.h/2, s.w, s.h);

    /* Refraction glint */
    const glint = X.createLinearGradient(-s.w/2,-s.h/2, s.w/2, s.h/2);
    glint.addColorStop(0, `rgba(255,255,255,${s.life * 0.5})`);
    glint.addColorStop(0.4, `rgba(255,255,255,0)`);
    X.fillStyle = glint;
    X.fillRect(-s.w/2, -s.h/2, s.w, s.h);

    X.globalAlpha = 1;
    X.restore();
  }

  if (!anyAlive) { shatterActive = false; }
}

/* ════════════════════════════════════════════════════
   SCENE 5 — WARP SPEED (inner page transition)
   Stars stretch into warp lines then snap to new page
   ════════════════════════════════════════════════════ */

let warpActive   = false;
let warpProgress = 0;
let warpLines    = [];
let warpHref     = null;
let warpNav      = false;

function startWarp(href) {
  if (warpActive) { location.href = href; return; }
  warpActive   = true;
  warpProgress = 0;
  warpHref     = href;
  warpNav      = false;

  const cx = W / 2, cy = H / 2;
  warpLines = Array.from({ length: 120 }, () => {
    const angle = rand(0, PI2);
    const startD = rand(20, 180);
    return {
      angle,
      startD,
      x: cx + Math.cos(angle) * startD,
      y: cy + Math.sin(angle) * startD,
      speed: rand(8, 24),
      len: rand(40, 200),
      hue: randInt(180, 260),
      alpha: rand(0.4, 0.9),
      w: rand(0.5, 2)
    };
  });
}

function drawWarp(dt) {
  if (!warpActive) return;
  warpProgress += dt * 1.4;
  const t = clamp(warpProgress, 0, 1);
  const cx = W / 2, cy = H / 2;

  /* Dark overlay builds */
  X.fillStyle = `rgba(0,0,8,${t * 0.92})`;
  X.fillRect(0, 0, W, H);

  /* Warp lines shoot from centre */
  for (const l of warpLines) {
    const dist = l.startD + t * l.speed * 80;
    const px1  = cx + Math.cos(l.angle) * dist;
    const py1  = cy + Math.sin(l.angle) * dist;
    const px2  = cx + Math.cos(l.angle) * (dist + l.len * t * 3);
    const py2  = cy + Math.sin(l.angle) * (dist + l.len * t * 3);

    const lg = X.createLinearGradient(px1, py1, px2, py2);
    lg.addColorStop(0, 'rgba(255,255,255,0)');
    lg.addColorStop(0.5, `hsla(${l.hue},90%,80%,${l.alpha * t})`);
    lg.addColorStop(1, `hsla(${l.hue},80%,100%,${l.alpha})`);

    X.beginPath();
    X.moveTo(px1, py1); X.lineTo(px2, py2);
    X.strokeStyle = lg;
    X.lineWidth = l.w * (1 + t * 3);
    X.stroke();
  }

  /* Central vortex */
  const vR = t * 120;
  const vg = X.createRadialGradient(cx, cy, 0, cx, cy, vR);
  vg.addColorStop(0, `rgba(255,255,255,${t * 0.9})`);
  vg.addColorStop(0.3, `rgba(100,200,255,${t * 0.5})`);
  vg.addColorStop(1, 'rgba(0,0,0,0)');
  X.beginPath(); X.arc(cx, cy, vR, 0, PI2);
  X.fillStyle = vg; X.fill();

  /* Navigate at peak */
  if (t > 0.65 && !warpNav) {
    warpNav = true;
    location.href = warpHref;
  }

  if (t >= 1) { warpActive = false; }
}

/* ════════════════════════════════════════════════════
   SCENE 6 — CONSTELLATION ASSEMBLE (dashboard entry)
   Stars draw lines like a constellation then UI fades in
   ════════════════════════════════════════════════════ */

let constellActive = false;
let constellProg   = 0;
let constellPts    = [];
let constellLines  = [];
let constellTarget = null;

function startConstellation(el) {
  constellActive = true;
  constellProg   = 0;
  constellTarget = el;
  el.style.opacity = '0';

  const rect = el.getBoundingClientRect();
  const N = 30;
  constellPts = Array.from({ length: N }, (_, i) => {
    const angle = rand(0, PI2);
    const dist  = rand(80, 320);
    const tx = rect.left + Math.random() * rect.width;
    const ty = rect.top  + Math.random() * rect.height;
    return {
      sx: W / 2 + Math.cos(angle) * dist,
      sy: H / 2 + Math.sin(angle) * dist,
      tx, ty,
      x: W / 2 + Math.cos(angle) * dist,
      y: H / 2 + Math.sin(angle) * dist,
      delay: i / N * 0.5,
      r: rand(1.5, 3.5),
      hue: randInt(180, 260)
    };
  });

  /* Connect some nearby points */
  constellLines = [];
  for (let i = 0; i < constellPts.length; i++) {
    const a = constellPts[i];
    for (let j = i + 1; j < constellPts.length; j++) {
      const b = constellPts[j];
      const d = Math.hypot(b.tx - a.tx, b.ty - a.ty);
      if (d < 120 && Math.random() > 0.5) {
        constellLines.push({ a: i, b: j });
      }
    }
  }
}

function drawConstellation(dt) {
  if (!constellActive) return;
  constellProg += dt * 0.6;
  const t = clamp(constellProg, 0, 1);

  /* Move points toward target */
  for (const p of constellPts) {
    const pt = clamp((t - p.delay) / (1 - p.delay), 0, 1);
    const ease = pt < 0.5 ? 2*pt*pt : -1+(4-2*pt)*pt;
    p.x = lerp(p.sx, p.tx, ease);
    p.y = lerp(p.sy, p.ty, ease);
  }

  /* Draw constellation lines */
  for (const l of constellLines) {
    const a = constellPts[l.a], b = constellPts[l.b];
    const pt = clamp((t - 0.2) / 0.8, 0, 1);
    const lg = X.createLinearGradient(a.x, a.y, b.x, b.y);
    lg.addColorStop(0, `rgba(100,180,255,${pt * 0.5})`);
    lg.addColorStop(1, `rgba(0,212,255,${pt * 0.3})`);
    X.beginPath();
    X.moveTo(a.x, a.y); X.lineTo(b.x, b.y);
    X.strokeStyle = lg; X.lineWidth = 0.8; X.stroke();
  }

  /* Draw star points */
  for (const p of constellPts) {
    const pt = clamp(t / 0.8, 0, 1);
    const gd = X.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r * 4);
    gd.addColorStop(0, `hsla(${p.hue},100%,90%,${pt})`);
    gd.addColorStop(0.5, `hsla(${p.hue},100%,70%,${pt * 0.5})`);
    gd.addColorStop(1, 'rgba(0,0,0,0)');
    X.beginPath(); X.arc(p.x, p.y, p.r * 4, 0, PI2);
    X.fillStyle = gd; X.fill();
    X.beginPath(); X.arc(p.x, p.y, p.r, 0, PI2);
    X.fillStyle = `hsla(${p.hue},100%,95%,${pt})`; X.fill();
  }

  /* Reveal UI element */
  if (t > 0.7 && constellTarget) {
    const revT = (t - 0.7) / 0.3;
    constellTarget.style.opacity = revT.toString();
    constellTarget.style.transform = `translateY(${(1 - revT) * 20}px)`;
  }

  if (t >= 1) { constellActive = false; if (constellTarget) { constellTarget.style.opacity='1'; constellTarget.style.transform=''; } }
}

/* ════════════════════════════════════════════════════
   MAIN RENDER LOOP
   ════════════════════════════════════════════════════ */

let lastTime = 0;

function loop(now) {
  const dt = Math.min((now - lastTime) / 1000, 0.05); // cap at 50ms
  lastTime = now;
  T += dt;

  X.clearRect(0, 0, W, H);

  drawNebula();

  if (voidActive)    drawVoidImplosion(dt);
  if (bangActive)    drawBigBang(dt);
  if (shatterActive) drawShatter(dt);
  if (warpActive)    drawWarp(dt);
  if (constellActive) drawConstellation(dt);

  requestAnimationFrame(loop);
}
requestAnimationFrame(loop);

/* ════════════════════════════════════════════════════
   PAGE DETECTION & WIRING
   ════════════════════════════════════════════════════ */

const PATH   = location.pathname;
const isIndex     = PATH.endsWith('index.php')     || PATH.endsWith('/') || PATH === '';
const isLogin     = document.body.classList.contains('ep-login');
const isDashboard = document.body.classList.contains('ep-dashboard');
const isInner     = document.body.classList.contains('ep-inner');

/* ── Index: role card click → void implosion ── */
if (isIndex) {
  document.querySelectorAll('.role-card').forEach(card => {
    card.addEventListener('click', function (e) {
      e.preventDefault();
      const href = this.getAttribute('href');
      const box  = document.querySelector('.main-box');

      /* Cards fly off */
      document.querySelectorAll('.role-card').forEach((c, i) => {
        setTimeout(() => {
          c.style.transition = 'transform 0.35s cubic-bezier(0.9,0,1,1), opacity 0.35s';
          const dirs = [[-1,-1],[1,-1],[-1,1],[1,1]];
          c.style.transform = `translate(${dirs[i][0]*300}px,${dirs[i][1]*200}px) scale(0.2) rotate(${dirs[i][0]*20}deg)`;
          c.style.opacity   = '0';
        }, i * 50);
      });

      /* Logo implodes */
      setTimeout(() => {
        const logo = document.querySelector('.landing-wrap');
        if (logo) {
          logo.style.transition = 'transform 0.6s cubic-bezier(0.9,0,1,1), opacity 0.5s';
          logo.style.transform = 'scale(0) rotate(720deg)';
          logo.style.opacity   = '0';
        }
        const cx = W / 2, cy = H / 2;
        startVoidImplosion(cx, cy, () => {
          location.href = href;
        });
      }, 250);
    });
  });

  /* 3D tilt on role cards */
  document.querySelectorAll('.role-card').forEach(card => {
    card.addEventListener('mousemove', function (e) {
      const r = this.getBoundingClientRect();
      const x = ((e.clientX - r.left) / r.width  - 0.5) * 20;
      const y = ((e.clientY - r.top)  / r.height - 0.5) * 20;
      this.style.transform = `perspective(600px) rotateX(${-y}deg) rotateY(${x}deg) translateY(-6px) scale(1.03)`;
      this.style.boxShadow = `0 20px 60px rgba(22,119,255,0.3), 0 0 30px rgba(22,119,255,0.15)`;
    });
    card.addEventListener('mouseleave', function () {
      this.style.transform = '';
      this.style.boxShadow = '';
    });
  });
}

/* ── Login pages: big bang entry ONLY ── */
/* IMPORTANT: form submits 100% natively. No JS interception whatsoever.
   PHP reads $_POST['login'] (the named button value) to detect submission.
   Any JS form.submit() or e.preventDefault() breaks this — so we do nothing. */
if (isLogin) {
  startBigBang();
}

/* ── Dashboard pages: constellation entry ── */
if (isDashboard) {
  const box = document.querySelector('.main-box');
  if (box) {
    box.style.transition = 'none';
    startConstellation(box);
  }
}

/* ── Inner pages: warp transition on all nav links ── */
function wireNavLinks() {
  const navSelectors = '.back-btn, .small-btn, .profile-circle, .lock-circle, .mail-icon, .quick-card a, .logout-row a, .icon-edit, .icon-delete';
  document.querySelectorAll(navSelectors).forEach(link => {
    link.addEventListener('click', function (e) {
      const href = this.getAttribute('href');
      if (!href || href === '#') return;
      e.preventDefault();
      startWarp(href);
    });
  });

  /* Inline approve/reject buttons that use ?param= URLs */
  document.querySelectorAll('.btn.green, .btn.red').forEach(btn => {
    if (btn.tagName === 'A') {
      btn.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        if (!href || href === '#') return;
        e.preventDefault();
        startWarp(href);
      });
    }
  });
}
wireNavLinks();

/* ── Spark burst on all button clicks ── */
function spawnSparks(cx, cy) {
  const sparks = Array.from({ length: 16 }, () => {
    const angle = rand(0, PI2);
    const speed = rand(2, 7);
    return { x: cx, y: cy, vx: Math.cos(angle)*speed, vy: Math.sin(angle)*speed - 1, r: rand(1,3), hue: randInt(180,260), life: 1 };
  });

  function tick() {
    let any = false;
    for (const s of sparks) {
      if (s.life <= 0) continue;
      any = true;
      s.vy += 0.08; s.vx *= 0.96; s.vy *= 0.96;
      s.x += s.vx; s.y += s.vy; s.life -= 0.05;
      const gd = X.createRadialGradient(s.x, s.y, 0, s.x, s.y, s.r * 2);
      gd.addColorStop(0, `hsla(${s.hue},100%,90%,${s.life})`);
      gd.addColorStop(1, 'rgba(0,0,0,0)');
      X.beginPath(); X.arc(s.x, s.y, s.r*2, 0, PI2);
      X.fillStyle = gd; X.fill();
    }
    if (any) requestAnimationFrame(tick);
  }
  requestAnimationFrame(tick);
}

document.querySelectorAll('.btn').forEach(btn => {
  btn.addEventListener('click', function (e) {
    spawnSparks(e.clientX, e.clientY);
  });
});

/* ── Input focus — local glow ring on canvas ── */
document.querySelectorAll('input, select, textarea').forEach(inp => {
  inp.addEventListener('focus', function () {
    const rect = this.getBoundingClientRect();
    const cx = rect.left + rect.width/2;
    const cy = rect.top  + rect.height/2;
    const w  = rect.width + 4;
    const h  = rect.height + 4;
    let   a  = 0;
    function pulse() {
      a += 0.08;
      if (a > Math.PI) return;
      const alpha = Math.sin(a) * 0.5;
      X.save();
      X.shadowColor = 'rgba(22,119,255,0.6)';
      X.shadowBlur  = 20;
      X.strokeStyle = `rgba(22,119,255,${alpha})`;
      X.lineWidth   = 2;
      X.beginPath();
      X.roundRect(cx - w/2, cy - h/2, w, h, 8);
      X.stroke();
      X.restore();
      requestAnimationFrame(pulse);
    }
    requestAnimationFrame(pulse);
  });
});

})();
