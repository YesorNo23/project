<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

<title>Waveform Forge — Ambient Nature Synth</title>
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<link rel="stylesheet" href="{{ asset('css/style2.css') }}">
<link rel="stylesheet" href="{{ asset('css/header.css') }}">
<link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">



<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Rajdhani:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/tone/14.8.49/Tone.js"></script>


<style>
  :root{
    --bg-0:#05070d;
    --bg-1:#0a0e18;
    --glass:rgba(255,255,255,0.045);
    --glass-strong:rgba(255,255,255,0.07);
    --glass-border:rgba(255,255,255,0.09);
    --text:#dbe6f5;
    --text-dim:#7686a3;
    --accent:#00fff2;
    --accent-2:#0aa2ff;
    --accent-soft: rgba(0,255,242,0.16);
    --tint: 5,7,13;
  }

  *{box-sizing:border-box;}
  html,body{
    margin:0; padding:0; height:100%; width:100%;
    background:radial-gradient(ellipse at 50% 20%, var(--bg-1) 0%, var(--bg-0) 65%);
    color:var(--text);
    font-family:'Rajdhani', sans-serif;
    overflow:hidden;
    -webkit-tap-highlight-color: transparent;
    transition: background-color .6s ease;
  }

  #stage{ position:fixed; inset:0; width:100%; height:100%; display:block; }
  #tintLayer{
    position:fixed; inset:0; pointer-events:none; z-index:1;
    background: radial-gradient(ellipse at 50% 30%, rgba(var(--tint),0.35) 0%, rgba(var(--tint),0) 70%);
    transition: background 1.2s ease;
  }
  #flash{
    position:fixed; inset:0; pointer-events:none; z-index:2;
    background:#fff; opacity:0; mix-blend-mode:overlay;
  }

  /* แก้ไข: เดิม .brand / #hint อยู่ที่ top:22px ซึ่งซ้อนทับกับ header ของเว็บหลัก
     (header.header-container ของไซต์ตั้ง position:fixed สูงประมาณ 80px ทับอยู่ด้านบนอยู่แล้ว
     จาก body{padding-top:80px} ที่ style.css ของเว็บกำหนดไว้)
     เลื่อนลงมาให้พ้นแนว header เพื่อไม่ให้ข้อความไปซ้อน/โผล่ทะลุกรอบ header */
  .brand{ position:fixed; top:96px; left:26px; z-index:5; pointer-events:none; user-select:none; max-width:60vw; }
  .brand h1{
    font-family:'Orbitron', sans-serif; font-size:20px; font-weight:700; letter-spacing:2px;
    margin:0; color:var(--text); text-shadow:0 0 18px var(--accent-soft);
  }
  .brand p{ margin:4px 0 0; font-size:13px; font-weight:500; color:var(--text-dim); letter-spacing:0.5px; }

  #hint{
    position:fixed; top:96px; right:26px; z-index:5;
    font-size:12px; color:var(--text-dim); text-align:right; max-width:240px; line-height:1.5;
    pointer-events:none;
  }
  @media (max-width:720px){ #hint{ display:none; } }

  /* ---------- control deck ---------- */
  #deck{
    position:fixed; left:50%; bottom:16px; transform:translateX(-50%);
    width:min(1040px, 95vw);
    max-width:100%;
    max-height: 62vh;
    background:var(--glass);
    border:1px solid var(--glass-border);
    border-radius:22px;
    backdrop-filter: blur(22px) saturate(160%);
    -webkit-backdrop-filter: blur(22px) saturate(160%);
    box-shadow: 0 20px 60px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.05);
    padding:6px 16px 14px;
    z-index:10;
    display:flex; flex-direction:column; gap:10px;
    /* แก้ไข: เดิมไม่มี overflow กำหนดไว้เลย ทำให้เนื้อหาที่สูงเกิน max-height (เช่นจอมือถือแนวนอน/จอเตี้ย)
       ล้นออกไปนอกกรอบ deck แทนที่จะเลื่อนดูได้ และล้นด้านข้างเวลาปุ่มไม่พอดีแถว */
    overflow-y:auto;
    overflow-x:hidden;
  }

  /* เพิ่ม: มือจับสำหรับพับ/กางแผงควบคุม เพื่อให้มองเห็น background/visualizer ด้านหลังได้เต็มจอ */
  .deck-handle{
    display:flex; align-items:center; justify-content:center; position:relative;
    cursor:pointer; padding:6px 0 2px; margin:0 -16px; flex-shrink:0;
  }
  .deck-handle-bar{ width:36px; height:4px; border-radius:3px; background:rgba(255,255,255,0.25); transition:background .2s ease; }
  .deck-handle:hover .deck-handle-bar{ background:rgba(255,255,255,0.45); }
  #deckToggleBtn{
    position:absolute; right:16px; top:50%; transform:translateY(-50%);
    width:26px; height:26px; border-radius:50%;
    background:transparent; border:1px solid var(--glass-border);
    color:var(--text-dim); display:flex; align-items:center; justify-content:center; cursor:pointer;
  }
  #deckToggleBtn svg{ width:14px; height:14px; transition: transform .3s ease; }
  /* แก้ไข: ตามที่ขอ ให้กดแล้วซ่อนแผงทั้งหมดจริงๆ (รวมมือจับ) ไม่ใช่แค่ย่อเนื้อหาข้างในเหลือแถบเปล่า
     เดิม .collapsed ซ่อนแค่ #deckBody ทำให้ยังเหลือแถบมือจับค้างอยู่บังพื้นหลังบางส่วน */
  body.deck-hidden #deck{ display:none; }
  #reopenBtn{
    position:fixed; left:50%; bottom:16px; transform:translateX(-50%);
    z-index:10; display:none;
    width:44px; height:44px; border-radius:50%;
    background:var(--glass-strong); border:1px solid var(--glass-border);
    backdrop-filter: blur(16px) saturate(160%);
    -webkit-backdrop-filter: blur(16px) saturate(160%);
    color:var(--text); align-items:center; justify-content:center; cursor:pointer;
    box-shadow:0 10px 30px rgba(0,0,0,0.4);
    transition: box-shadow .2s ease, border-color .2s ease;
  }
  #reopenBtn:hover{ border-color:var(--accent); box-shadow:0 0 20px var(--accent-soft); }
  #reopenBtn svg{ width:18px; height:18px; }
  body.deck-hidden #reopenBtn{ display:flex; }

  .deck-row{ display:flex; align-items:center; gap:14px; flex-wrap:wrap; max-width:100%; }
  .group-label{ font-size:12px; color:var(--text-dim); font-weight:600; min-width:64px; letter-spacing:0.3px; flex-shrink:0; }

  /* แก้ไข: เดิม .seg ไม่มี flex-wrap ทำให้กลุ่มปุ่ม (โหมด/waveform/ธีม) เป็นก้อนเดียวที่ไม่ยอมตัดบรรทัด
     พอจอแคบลงจึงล้นขอบขวาของ deck ออกไป (นี่คือสาเหตุหลักของปุ่มที่ "ไม่อยู่ในกรอบ deck") */
  .seg{ display:flex; gap:6px; flex-wrap:wrap; min-width:0; }
  .theme-swatch, .bg-swatch{
    width:18px; height:18px; border-radius:50%; border:2px solid rgba(255,255,255,0.15); cursor:pointer;
    transition: transform .15s ease, border-color .2s ease; flex-shrink:0;
  }
  .theme-swatch:hover, .bg-swatch:hover{ transform:scale(1.12); }
  .theme-swatch.active, .bg-swatch.active{ border-color:#fff; }
  #bgColorInput{
    width:26px; height:26px; padding:0; border:2px solid rgba(255,255,255,0.15); border-radius:50%;
    background:none; cursor:pointer; overflow:hidden; flex-shrink:0;
  }
  #bgColorInput::-webkit-color-swatch-wrapper{ padding:0; }
  #bgColorInput::-webkit-color-swatch{ border:none; border-radius:50%; }

  .slider-block{ display:flex; align-items:center; gap:10px; flex:1 1 170px; min-width:150px; }
  .slider-block .val{ font-size:12px; color:var(--text-dim); width:48px; text-align:right; flex-shrink:0; font-variant-numeric: tabular-nums; }
  input[type="range"]{
    -webkit-appearance:none; appearance:none; flex:1; height:3px; border-radius:3px;
    background: rgba(255,255,255,0.14); outline:none; cursor:pointer; min-width:0;
  }
  input[type="range"]::-webkit-slider-thumb{
    -webkit-appearance:none; appearance:none; width:13px; height:13px; border-radius:50%;
    background: var(--accent); box-shadow: 0 0 10px var(--accent-soft); cursor:pointer; border: 2px solid rgba(0,0,0,0.3);
  }
  input[type="range"]::-moz-range-thumb{
    width:13px; height:13px; border-radius:50%; background: var(--accent);
    box-shadow: 0 0 10px var(--accent-soft); cursor:pointer; border: 2px solid rgba(0,0,0,0.3);
  }

  hr.div{ border:none; height:1px; background:var(--glass-border); margin:2px 0; flex-shrink:0; }

  /* ---------- transport + category tabs ---------- */
  .transport{ display:flex; align-items:center; gap:10px; flex-shrink:0; }
  #playBtn{
    width:46px; height:46px; border-radius:50%;
    border:1px solid var(--glass-border);
    background:linear-gradient(180deg, var(--glass-strong), var(--glass));
    color:var(--accent); display:flex; align-items:center; justify-content:center;
    cursor:pointer; flex-shrink:0;
    transition: box-shadow .3s ease, border-color .3s ease;
  }
  #playBtn.on{ box-shadow: 0 0 22px var(--accent-soft), inset 0 0 10px var(--accent-soft); border-color: var(--accent); }
  #playBtn svg{ width:18px; height:18px; }

  #catTabs{ display:flex; gap:6px; flex-wrap:wrap; }
  #catTabs button{
    font-family:'Rajdhani', sans-serif; font-size:13px; font-weight:600; color:var(--text-dim);
    background:transparent; border:1px solid var(--glass-border); border-radius:10px;
    padding:8px 14px; cursor:pointer; display:flex; align-items:center; gap:6px;
    transition: all .2s ease; white-space:nowrap;
  }
  #catTabs button.active{ color:var(--bg-0); background:var(--accent); border-color:var(--accent); box-shadow:0 0 16px var(--accent-soft); }
  #catTabs button:hover:not(.active){ color:var(--text); border-color:rgba(255,255,255,0.25); }

  #soundGrid{
    display:grid; grid-template-columns:repeat(auto-fill, minmax(150px,1fr)); gap:8px;
    max-height:26vh; overflow-y:auto; padding:2px 2px 4px;
  }
  .sound-btn{
    display:flex; align-items:center; gap:8px;
    background:rgba(255,255,255,0.03); border:1px solid var(--glass-border); border-radius:12px;
    padding:10px 12px; cursor:pointer; text-align:left;
    transition: all .2s ease;
    /* แก้ไข: flex item มีค่า min-width:auto โดยปริยาย ทำให้ไม่ยอมหดเล็กกว่าความกว้างของข้อความ
       เวลาป้ายชื่อเสียงยาว (เช่น "เสียงนกนางนวล") จะดันปุ่มให้กว้างล้นออกไปนอกช่อง grid
       ใส่ min-width:0 เพื่อให้ปุ่มหดตามช่อง แล้วให้ตัวหนังสือตัดคำแทน */
    min-width:0;
  }
  .sound-btn > span:last-child{ min-width:0; overflow:hidden; }
  .sound-btn .icon{ font-size:18px; flex-shrink:0; filter: grayscale(0.3); opacity:0.85; }
  .sound-btn .label{ font-size:13.5px; font-weight:600; color:var(--text); line-height:1.2; overflow-wrap:anywhere; word-break:break-word; }
  .sound-btn .sub{ font-size:10.5px; color:var(--text-dim); margin-top:1px; overflow-wrap:anywhere; word-break:break-word; }
  .sound-btn:hover{ border-color:rgba(255,255,255,0.25); background:rgba(255,255,255,0.055); }
  .sound-btn.active{
    border-color:var(--accent); background:var(--accent-soft);
    box-shadow:0 0 18px var(--accent-soft), inset 0 0 12px rgba(255,255,255,0.04);
  }
  .sound-btn.active .icon{ filter:none; }

  #soundGrid::-webkit-scrollbar{ width:6px; }
  #soundGrid::-webkit-scrollbar-thumb{ background:rgba(255,255,255,0.15); border-radius:6px; }

  /* เพิ่ม: ปุ่มสำรองไว้เผื่อผู้ใช้พับแผงควบคุมจนสุด ยังมีทางเรียกกลับมาได้เสมอ
     แม้ #deck จะยังอยู่ (แค่ย่อ) แต่กันไว้กรณีจอเล็กมากจน handle ถูกบัง */
  @media (max-width: 480px){
    .brand{ top:88px; left:16px; }
    #hint{ display:none; }
    #deck{ bottom:10px; }
  }
</style>
</head>
<body>
@include('user/header')
@include('user/userprofile')
@include('user/changepassword')

<canvas id="stage"></canvas>
<div id="tintLayer"></div>
<div id="flash"></div>

<div class="brand">
  <h1>สร้างคลืนเสียง</h1>
  <p></p>
</div>


<div id="deck">

  <!-- เพิ่ม: มือจับพับ/กางแผงควบคุม กดที่แถบนี้เพื่อซ่อนแผงทั้งหมดแล้วดู background/visualizer เต็มจอ -->
  <div id="deckHandle" class="deck-handle" role="button" tabindex="0" aria-label="ซ่อน/แสดงแผงควบคุม">
    <span class="deck-handle-bar"></span>
    <button id="deckToggleBtn" aria-hidden="true" tabindex="-1">
      <svg id="deckToggleIcon" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
    </button>
  </div>

  <div id="deckBody" style="display:flex; flex-direction:column; gap:10px; min-width:0;">

    <div class="deck-row">
      <div class="seg" id="modeSeg">
        <button data-mode="nature" class="active">เสียงธรรมชาติ</button>
        <button data-mode="synth">คลื่นเสียงปกติ</button>
      </div>
    </div>

    <div class="deck-row">
      <span class="group-label">Theme</span>
      <div class="seg">
        <div class="theme-swatch active" data-theme="cyan" style="background:#00fff2;"></div>
        <div class="theme-swatch" data-theme="magenta" style="background:#ff2bd6;"></div>
        <div class="theme-swatch" data-theme="green" style="background:#39ff14;"></div>
      </div>

      <div class="slider-block">
        <span class="group-label">Volume</span>
        <input type="range" id="volSlider" min="0" max="100" step="1" value="70">
        <span class="val" id="volVal">70%</span>
      </div>
    </div>

    <!-- เพิ่ม: เลือกเปลี่ยนสีพื้นหลังของหน้าเว็บ/visualizer ได้เอง (สวอตช์สำเร็จรูป + custom color picker) -->
    <div class="deck-row">
      <span class="group-label">Background</span>
      <div class="seg" id="bgSwatches"></div>
      <input type="color" id="bgColorInput" value="#05070d" title="กำหนดสีพื้นหลังเอง">
    </div>

    <div class="deck-row">
      <div class="slider-block">
        <span class="group-label">Wave speed</span>
        <input type="range" id="speedSlider" min="0" max="10" step="0.1" value="3">
        <span class="val" id="speedVal">3.0</span>
      </div>
      <div class="slider-block">
        <span class="group-label">Amplitude</span>
        <input type="range" id="ampSlider" min="0.5" max="3" step="0.1" value="1.5">
        <span class="val" id="ampVal">1.5</span>
      </div>
      <div class="slider-block">
        <span class="group-label">Reverb</span>
        <input type="range" id="reverbSlider" min="0" max="100" step="1" value="35">
        <span class="val" id="reverbVal">35%</span>
      </div>
      <div class="slider-block">
        <span class="group-label">Delay</span>
        <input type="range" id="delaySlider" min="0" max="100" step="1" value="15">
        <span class="val" id="delayVal">15%</span>
      </div>
    </div>

    <hr class="div">

    <div id="natureSection" style="display:flex; flex-direction:column; gap:10px; min-width:0;">
      <div id="catTabs"></div>
      <div id="soundGrid"></div>
    </div>

    <div id="synthSection" style="display:none; flex-direction:column; gap:10px; min-width:0;">
      <div class="deck-row">
        <div class="transport">
          <button id="playBtn" aria-label="play">
            <svg id="playIcon" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
          </button>
        </div>
        <span class="group-label">Waveform</span>
        <div class="seg" id="waveSeg">
          <button data-wave="sine" class="active">Sine</button>
          <button data-wave="square">Square</button>
          <button data-wave="triangle">Triangle</button>
          <button data-wave="sawtooth">Sawtooth</button>
        </div>
      </div>
      <div class="deck-row">
        <div class="slider-block">
          <span class="group-label">Frequency</span>
          <input type="range" id="freqSlider" min="55" max="1200" step="1" value="220">
          <span class="val" id="freqVal">220 Hz</span>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- เพิ่ม: ปุ่มลอยไว้เรียกแผงควบคุมกลับมา หลังจากซ่อนแผงทั้งหมดไปแล้ว -->
<button id="reopenBtn" aria-label="แสดงแผงควบคุม">
  <svg viewBox="0 0 24 24" fill="currentColor"><path d="M7 14l5-5 5 5z"/></svg>
</button>

<script>
/* =========================================================
   THEME (visualizer line color)
========================================================= */
const THEMES = {
  cyan:    { accent:'#00fff2', accent2:'#0aa2ff', soft:'rgba(0,255,242,0.16)' },
  magenta: { accent:'#ff2bd6', accent2:'#b400ff', soft:'rgba(255,43,214,0.18)' },
  green:   { accent:'#39ff14', accent2:'#00ff9d', soft:'rgba(57,255,20,0.16)' },
};
let currentTheme = THEMES.cyan;
function applyTheme(name){
  currentTheme = THEMES[name];
  const root = document.documentElement.style;
  root.setProperty('--accent', currentTheme.accent);
  root.setProperty('--accent-2', currentTheme.accent2);
  root.setProperty('--accent-soft', currentTheme.soft);
  document.querySelectorAll('.theme-swatch').forEach(el=> el.classList.toggle('active', el.dataset.theme === name));
}
document.querySelectorAll('.theme-swatch').forEach(el=> el.addEventListener('click', ()=> applyTheme(el.dataset.theme)));

/* ---- background color picker ---- */
function hexToRgb(hex){
  hex = hex.replace('#','');
  if (hex.length === 3) hex = hex.split('').map(c=>c+c).join('');
  const n = parseInt(hex,16);
  return { r:(n>>16)&255, g:(n>>8)&255, b:n&255 };
}
function rgbToHex(r,g,b){
  return '#'+[r,g,b].map(v=>Math.max(0,Math.min(255,Math.round(v))).toString(16).padStart(2,'0')).join('');
}
function shade(hex, percent){
  const { r,g,b } = hexToRgb(hex);
  const t = percent < 0 ? 0 : 255;
  const p = Math.abs(percent);
  return rgbToHex(r+(t-r)*p, g+(t-g)*p, b+(t-b)*p);
}
function applyBgColor(hex){
  document.documentElement.style.setProperty('--bg-0', hex);
  document.documentElement.style.setProperty('--bg-1', shade(hex, 0.22));
}
const BG_PRESETS = [
  { id:'midnight', hex:'#05070d' },
  { id:'purple',   hex:'#150a24' },
  { id:'navy',     hex:'#031a2b' },
  { id:'forest',   hex:'#07160e' },
  { id:'slate',    hex:'#111318' },
];
const bgSwatchContainer = document.getElementById('bgSwatches');
const bgColorInput = document.getElementById('bgColorInput');
BG_PRESETS.forEach((p, i)=>{
  const el = document.createElement('div');
  el.className = 'bg-swatch' + (i===0 ? ' active' : '');
  el.style.background = p.hex;
  el.title = p.id;
  el.addEventListener('click', ()=>{
    document.querySelectorAll('.bg-swatch').forEach(s=>s.classList.remove('active'));
    el.classList.add('active');
    bgColorInput.value = p.hex;
    applyBgColor(p.hex);
  });
  bgSwatchContainer.appendChild(el);
});
bgColorInput.addEventListener('input', ()=>{
  document.querySelectorAll('.bg-swatch').forEach(s=>s.classList.remove('active'));
  applyBgColor(bgColorInput.value);
});

/* ---- deck collapse / expand handle ---- */
const deckEl = document.getElementById('deck');
const deckHandleEl = document.getElementById('deckHandle');
const reopenBtn = document.getElementById('reopenBtn');
function hideDeck(){ document.body.classList.add('deck-hidden'); }
function showDeck(){ document.body.classList.remove('deck-hidden'); }
deckHandleEl.addEventListener('click', hideDeck);
deckHandleEl.addEventListener('keydown', (e)=>{ if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); hideDeck(); } });
reopenBtn.addEventListener('click', showDeck);

/* ---- mode switch (nature ambience vs. standard wave generator) ---- */
document.querySelectorAll('#modeSeg button').forEach(btn=>{
  btn.addEventListener('click', ()=>{
    document.querySelectorAll('#modeSeg button').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    const mode = btn.dataset.mode;
    document.getElementById('natureSection').style.display = mode === 'nature' ? 'flex' : 'none';
    document.getElementById('synthSection').style.display = mode === 'synth' ? 'flex' : 'none';
  });
});

/* =========================================================
   AUDIO ENGINE
========================================================= */
let audioReady = false;
let ambientBus, delay, reverb, waveform, limiter;
let drone, dronePlaying = false;

function buildAudioGraph(){
  if (audioReady) return;
  waveform = new Tone.Waveform(1024);
  limiter  = new Tone.Limiter(-2);
  reverb   = new Tone.Freeverb({ roomSize: 0.75, dampening: 2500, wet: 0.35 });
  delay    = new Tone.FeedbackDelay({ delayTime: 0.32, feedback: 0.28, wet: 0.15 });
  ambientBus = new Tone.Gain(1);

  ambientBus.connect(delay);
  delay.connect(reverb);
  reverb.connect(limiter);
  limiter.connect(waveform);
  waveform.connect(Tone.Destination);

  drone = new Tone.Oscillator({ frequency: 220, type: 'sine', volume: -8 });
  drone.connect(ambientBus);

  Tone.Destination.volume.value = Tone.gainToDb(0.7);
  audioReady = true;
}
async function ensureAudioContext(){
  if (Tone.context.state !== 'running') await Tone.start();
  buildAudioGraph();
}

/* ---- standard wave generator controls ---- */
const playBtn = document.getElementById('playBtn');
const playIcon = document.getElementById('playIcon');
const ICON_PLAY = '<path d="M8 5v14l11-7z"/>';
const ICON_PAUSE = '<path d="M6 5h4v14H6zM14 5h4v14h-4z"/>';

playBtn.addEventListener('click', async ()=>{
  await ensureAudioContext();
  if (!dronePlaying){
    drone.start();
    dronePlaying = true;
    playBtn.classList.add('on');
    playIcon.innerHTML = ICON_PAUSE;
  } else {
    drone.stop();
    dronePlaying = false;
    playBtn.classList.remove('on');
    playIcon.innerHTML = ICON_PLAY;
  }
});

document.querySelectorAll('#waveSeg button').forEach(btn=>{
  btn.addEventListener('click', async ()=>{
    await ensureAudioContext();
    document.querySelectorAll('#waveSeg button').forEach(b=>b.classList.remove('active'));
    btn.classList.add('active');
    drone.type = btn.dataset.wave;
  });
});

const freqSlider = document.getElementById('freqSlider');
const freqVal = document.getElementById('freqVal');
freqSlider.addEventListener('input', async ()=>{
  await ensureAudioContext();
  const f = Number(freqSlider.value);
  freqVal.textContent = `${f} Hz`;
  drone.frequency.rampTo(f, 0.05);
});

/* ---- master sliders ---- */
const volSlider = document.getElementById('volSlider');
const volVal = document.getElementById('volVal');
volSlider.addEventListener('input', async ()=>{
  await ensureAudioContext();
  const v = Number(volSlider.value);
  volVal.textContent = `${v}%`;
  Tone.Destination.volume.value = v === 0 ? -60 : Tone.gainToDb(v/100);
});
const reverbSlider = document.getElementById('reverbSlider');
const reverbVal = document.getElementById('reverbVal');
reverbSlider.addEventListener('input', async ()=>{
  await ensureAudioContext();
  const v = Number(reverbSlider.value);
  reverbVal.textContent = `${v}%`;
  reverb.wet.value = v/100;
});
const delaySlider = document.getElementById('delaySlider');
const delayVal = document.getElementById('delayVal');
delaySlider.addEventListener('input', async ()=>{
  await ensureAudioContext();
  const v = Number(delaySlider.value);
  delayVal.textContent = `${v}%`;
  delay.wet.value = v/100;
});
let waveSpeed = 3.0, ampScale = 1.5;
const speedSlider = document.getElementById('speedSlider');
const speedVal = document.getElementById('speedVal');
speedSlider.addEventListener('input', ()=>{ waveSpeed = Number(speedSlider.value); speedVal.textContent = waveSpeed.toFixed(1); });
const ampSlider = document.getElementById('ampSlider');
const ampVal = document.getElementById('ampVal');
ampSlider.addEventListener('input', ()=>{ ampScale = Number(ampSlider.value); ampVal.textContent = ampScale.toFixed(1); });

/* =========================================================
   SOUND-LAYER KITS  (procedural, continuous, non-repeating)
========================================================= */
const visualEvents = []; // {kind, strength}

function scheduleRandom(fn, minI, maxI){
  let stopped = false;
  let timer = null;
  const tick = ()=>{
    if (stopped) return;
    fn();
    const wait = (minI + Math.random()*(maxI-minI)) * 1000;
    timer = setTimeout(tick, wait);
  };
  timer = setTimeout(tick, (minI + Math.random()*(maxI-minI))*1000);
  return { stop(){ stopped = true; if(timer) clearTimeout(timer); } };
}

function kitNoiseBed(bus, {type='pink', filterType='lowpass', freq=800, lfoRate=0.08, lfoDepth=400, vol=-18}){
  const noise = new Tone.Noise(type).start();
  const filter = new Tone.Filter(freq, filterType);
  const gain = new Tone.Volume(vol);
  const lfo = new Tone.LFO(lfoRate, Math.max(20,freq-lfoDepth), freq+lfoDepth).start();
  lfo.connect(filter.frequency);
  noise.connect(filter); filter.connect(gain); gain.connect(bus);
  return { stop(){ noise.stop(); lfo.stop(); [noise,filter,gain,lfo].forEach(n=>n.dispose()); } };
}

function kitTremoloBed(bus, {type='white', freq=4000, filterType='bandpass', tremRate=6, vol=-22}){
  const noise = new Tone.Noise(type).start();
  const filter = new Tone.Filter(freq, filterType);
  const gain = new Tone.Volume(vol);
  const trem = new Tone.Tremolo(tremRate, 0.8).start();
  noise.connect(filter); filter.connect(trem); trem.connect(gain); gain.connect(bus);
  return { stop(){ noise.stop(); [noise,filter,trem,gain].forEach(n=>n.dispose()); } };
}

function kitChirps(bus, {freqMin=1800, freqMax=3200, durMin=0.08, durMax=0.18, iMin=1.5, iMax=5, vol=-16, wave='sine', visual='bird'}){
  const synth = new Tone.Synth({ oscillator:{type:wave}, envelope:{attack:0.005, decay:0.08, sustain:0, release:0.08}, volume:vol }).connect(bus);
  const sched = scheduleRandom(()=>{
    const f = freqMin + Math.random()*(freqMax-freqMin);
    const d = durMin + Math.random()*(durMax-durMin);
    synth.triggerAttackRelease(f, d);
    visualEvents.push({ kind: visual, strength: 0.6 + Math.random()*0.6 });
  }, iMin, iMax);
  return { stop(){ sched.stop(); synth.dispose(); } };
}

function kitKnocks(bus, {freqMin=90, freqMax=160, iMin=3, iMax=9, vol=-10, visual='leaf'}){
  const synth = new Tone.MembraneSynth({ pitchDecay:0.05, octaves:2, volume:vol }).connect(bus);
  const sched = scheduleRandom(()=>{
    const f = freqMin + Math.random()*(freqMax-freqMin);
    synth.triggerAttackRelease(f, 0.12);
    visualEvents.push({ kind: visual, strength: 0.5 + Math.random()*0.5 });
  }, iMin, iMax);
  return { stop(){ sched.stop(); synth.dispose(); } };
}

function kitBurst(bus, {iMin=10, iMax=25, vol=-6, visual='thunder'}){
  const synth = new Tone.NoiseSynth({ noise:{type:'brown'}, envelope:{attack:0.02, decay:1.2, sustain:0.05, release:1.0}, volume:vol }).connect(bus);
  const filter = new Tone.Filter(400,'lowpass');
  synth.disconnect(); synth.connect(filter); filter.connect(bus);
  const sched = scheduleRandom(()=>{
    synth.triggerAttackRelease(1.4 + Math.random()*1.2);
    visualEvents.push({ kind: visual, strength: 0.8 + Math.random()*0.4 });
  }, iMin, iMax);
  return { stop(){ sched.stop(); synth.dispose(); filter.dispose(); } };
}

function kitDrone(bus, {freq=90, type='sine', vol=-20}){
  const osc = new Tone.Oscillator(freq, type).start();
  const gain = new Tone.Volume(vol);
  osc.connect(gain); gain.connect(bus);
  return { stop(){ osc.stop(); [osc,gain].forEach(n=>n.dispose()); } };
}

function kitTwinkle(bus, {iMin=4, iMax=10, vol=-24, visual='star'}){
  const synth = new Tone.Synth({ oscillator:{type:'sine'}, envelope:{attack:0.4, decay:0.6, sustain:0.1, release:1.2}, volume:vol }).connect(bus);
  const notes = [880,988,1046,1318,1568];
  const sched = scheduleRandom(()=>{
    const f = notes[Math.floor(Math.random()*notes.length)];
    synth.triggerAttackRelease(f, 1.5);
    visualEvents.push({ kind: visual, strength: 0.4 + Math.random()*0.4 });
  }, iMin, iMax);
  return { stop(){ sched.stop(); synth.dispose(); } };
}

/* combine multiple kits into one handle */
function combine(...handles){ return { stop(){ handles.forEach(h=>h.stop()); } }; }

/* =========================================================
   SOUND PRESET LIBRARY
========================================================= */
const CATEGORIES = [
  { id:'sea',    label:'ทะเล',        icon:'🌊' },
  { id:'forest', label:'ป่าไม้',       icon:'🌲' },
  { id:'season', label:'ฤดูกาล',      icon:'🍂' },
  { id:'sky',    label:'ท้องฟ้า',      icon:'☁️' },
  { id:'time',   label:'ช่วงเวลา',     icon:'🕐' },
];

const PRESETS = [
  // ---- ทะเล ----
  { id:'sea_waves', cat:'sea', icon:'🌊', label:'คลื่นทะเล', sub:'Ocean Waves', visual:'wave', tint:'6,60,80',
    build:(bus)=> combine(
      kitNoiseBed(bus,{type:'pink', filterType:'lowpass', freq:500, lfoRate:0.09, lfoDepth:350, vol:-12}),
      kitTremoloBed(bus,{type:'white', freq:2200, tremRate:0.5, vol:-26})
    )},
  { id:'sea_breeze', cat:'sea', icon:'🌬️', label:'ลมทะเล', sub:'Sea Breeze', visual:'wind', tint:'20,90,110',
    build:(bus)=> kitNoiseBed(bus,{type:'pink', filterType:'lowpass', freq:1300, lfoRate:0.16, lfoDepth:650, vol:-18}) },
  { id:'sea_deep', cat:'sea', icon:'🫧', label:'น้ำลึก', sub:'Deep Water', visual:'bubble', tint:'4,20,45',
    build:(bus)=> kitNoiseBed(bus,{type:'brown', filterType:'lowpass', freq:140, lfoRate:0.04, lfoDepth:60, vol:-14}) },
  { id:'sea_gulls', cat:'sea', icon:'🐦', label:'นกนางนวล', sub:'Distant Gulls', visual:'bird', tint:'40,110,140',
    build:(bus)=> kitChirps(bus,{freqMin:1100, freqMax:1900, durMin:0.15, durMax:0.3, iMin:4, iMax:10, vol:-18, wave:'triangle', visual:'bird'}) },

  // ---- ป่าไม้ ----
  { id:'forest_branch', cat:'forest', icon:'🌿', label:'กิ่งไม้', sub:'Creaking Branches', visual:'leaf', tint:'25,55,30',
    build:(bus)=> kitKnocks(bus,{freqMin:70, freqMax:140, iMin:3, iMax:9, vol:-8, visual:'leaf'}) },
  { id:'forest_birds', cat:'forest', icon:'🐤', label:'เสียงนก', sub:'Forest Birds', visual:'bird', tint:'20,60,25',
    build:(bus)=> kitChirps(bus,{freqMin:2200, freqMax:4200, durMin:0.06, durMax:0.16, iMin:1, iMax:4, vol:-14, wave:'sine', visual:'bird'}) },
  { id:'forest_insects', cat:'forest', icon:'🦗', label:'เสียงแมลง', sub:'Insects', visual:'insect', tint:'30,45,15',
    build:(bus)=> kitTremoloBed(bus,{type:'white', freq:4200, tremRate:14, vol:-22}) },
  { id:'forest_stream', cat:'forest', icon:'💧', label:'ลำธาร', sub:'Stream Water', visual:'bubble', tint:'10,55,55',
    build:(bus)=> kitNoiseBed(bus,{type:'pink', filterType:'highpass', freq:2000, lfoRate:0.6, lfoDepth:1000, vol:-16}) },
  { id:'forest_leaves', cat:'forest', icon:'🍃', label:'ใบไม้ไหว', sub:'Rustling Leaves', visual:'leaf', tint:'20,50,20',
    build:(bus)=> kitNoiseBed(bus,{type:'white', filterType:'highpass', freq:3200, lfoRate:0.3, lfoDepth:1500, vol:-24}) },

  // ---- ฤดูกาล ----
  { id:'season_rain', cat:'season', icon:'⛈️', label:'ฝนฟ้าร้อง', sub:'Thunderstorm', visual:'rain', tint:'30,35,55',
    build:(bus)=> combine(
      kitNoiseBed(bus,{type:'pink', filterType:'lowpass', freq:1000, lfoRate:0.1, lfoDepth:300, vol:-11}),
      kitTremoloBed(bus,{type:'white', freq:5500, tremRate:22, vol:-20}),
      kitBurst(bus,{iMin:9, iMax:22, vol:-4, visual:'thunder'})
    )},
  { id:'season_summer', cat:'season', icon:'☀️', label:'ฤดูร้อน', sub:'Summer Heat', visual:'insect', tint:'70,55,15',
    build:(bus)=> combine(
      kitTremoloBed(bus,{type:'white', freq:4500, tremRate:16, vol:-18}),
      kitNoiseBed(bus,{type:'pink', filterType:'lowpass', freq:900, lfoRate:0.05, lfoDepth:200, vol:-26})
    )},
  { id:'season_snow', cat:'season', icon:'❄️', label:'พายุหิมะ', sub:'Snow Storm Wind', visual:'snow', tint:'50,60,80',
    build:(bus)=> kitNoiseBed(bus,{type:'white', filterType:'lowpass', freq:750, lfoRate:0.25, lfoDepth:500, vol:-9}) },
  { id:'season_spring', cat:'season', icon:'🌸', label:'ใบไม้ผลิ', sub:'Spring Bloom', visual:'bird', tint:'60,40,55',
    build:(bus)=> combine(
      kitChirps(bus,{freqMin:2400, freqMax:4000, durMin:0.06, durMax:0.14, iMin:1.5, iMax:4.5, vol:-16, wave:'sine', visual:'bird'}),
      kitNoiseBed(bus,{type:'pink', filterType:'highpass', freq:1800, lfoRate:0.5, lfoDepth:900, vol:-22})
    )},
  { id:'season_autumn', cat:'season', icon:'🍁', label:'ใบไม้ร่วง', sub:'Autumn Rustle', visual:'leaf', tint:'60,35,10',
    build:(bus)=> combine(
      kitNoiseBed(bus,{type:'pink', filterType:'highpass', freq:2500, lfoRate:0.2, lfoDepth:1200, vol:-20}),
      kitKnocks(bus,{freqMin:120, freqMax:220, iMin:4, iMax:10, vol:-16, visual:'leaf'})
    )},

  // ---- ท้องฟ้า ----
  { id:'sky_atmosphere', cat:'sky', icon:'🌫️', label:'ชั้นบรรยากาศ', sub:'High Atmosphere', visual:'wind', tint:'35,45,60',
    build:(bus)=> kitNoiseBed(bus,{type:'pink', filterType:'highpass', freq:5000, lfoRate:0.06, lfoDepth:800, vol:-24}) },
  { id:'sky_airplane', cat:'sky', icon:'✈️', label:'เครื่องบิน', sub:'Airplane Cabin', visual:'drone', tint:'40,40,45',
    build:(bus)=> combine(
      kitDrone(bus,{freq:100, type:'sawtooth', vol:-24}),
      kitNoiseBed(bus,{type:'brown', filterType:'lowpass', freq:320, lfoRate:0.03, lfoDepth:40, vol:-14})
    )},
  { id:'sky_wind', cat:'sky', icon:'💨', label:'ลมบนฟ้า', sub:'High-altitude Wind', visual:'wind', tint:'30,55,70',
    build:(bus)=> kitNoiseBed(bus,{type:'pink', filterType:'bandpass', freq:1500, lfoRate:0.2, lfoDepth:800, vol:-16}) },
  { id:'sky_thunder', cat:'sky', icon:'🌩️', label:'ฟ้าร้องไกล', sub:'Distant Thunder', visual:'thunder', tint:'35,25,50',
    build:(bus)=> combine(
      kitNoiseBed(bus,{type:'brown', filterType:'lowpass', freq:200, lfoRate:0.05, lfoDepth:60, vol:-26}),
      kitBurst(bus,{iMin:14, iMax:32, vol:-9, visual:'thunder'})
    )},

  // ---- ช่วงเวลา ----
  { id:'time_morning', cat:'time', icon:'🌅', label:'ยามเช้า', sub:'Morning', visual:'bird', tint:'80,60,25',
    build:(bus)=> combine(
      kitChirps(bus,{freqMin:2200, freqMax:3800, durMin:0.06, durMax:0.15, iMin:2, iMax:6, vol:-15, wave:'sine', visual:'bird'}),
      kitNoiseBed(bus,{type:'pink', filterType:'lowpass', freq:1000, lfoRate:0.08, lfoDepth:250, vol:-28})
    )},
  { id:'time_midday', cat:'time', icon:'🏙️', label:'ยามสาย', sub:'Midday', visual:'insect', tint:'40,65,85',
    build:(bus)=> combine(
      kitTremoloBed(bus,{type:'white', freq:4000, tremRate:12, vol:-22}),
      kitNoiseBed(bus,{type:'pink', filterType:'bandpass', freq:1400, lfoRate:0.15, lfoDepth:500, vol:-22})
    )},
  { id:'time_evening', cat:'time', icon:'🌇', label:'ยามเย็น', sub:'Evening', visual:'firefly', tint:'75,40,55',
    build:(bus)=> combine(
      kitChirps(bus,{freqMin:900, freqMax:1500, durMin:0.15, durMax:0.3, iMin:0.6, iMax:2, vol:-18, wave:'triangle', visual:'firefly'}),
      kitNoiseBed(bus,{type:'pink', filterType:'lowpass', freq:800, lfoRate:0.1, lfoDepth:200, vol:-26})
    )},
  { id:'time_night', cat:'time', icon:'🌙', label:'กลางคืน', sub:'Night', visual:'star', tint:'10,15,45',
    build:(bus)=> combine(
      kitDrone(bus,{freq:60, type:'sine', vol:-30}),
      kitChirps(bus,{freqMin:1000, freqMax:1600, durMin:0.15, durMax:0.35, iMin:0.8, iMax:2.5, vol:-20, wave:'triangle', visual:'insect'}),
      kitTwinkle(bus,{iMin:5, iMax:12, vol:-26, visual:'star'})
    )},
];

/* =========================================================
   UI: CATEGORY TABS + SOUND GRID
========================================================= */
const catTabsEl = document.getElementById('catTabs');
const soundGridEl = document.getElementById('soundGrid');
const activeSounds = new Map(); // id -> handle
let currentCat = 'sea';

function renderTabs(){
  catTabsEl.innerHTML = '';
  CATEGORIES.forEach(c=>{
    const btn = document.createElement('button');
    btn.textContent = `${c.icon} ${c.label}`;
    btn.className = c.id === currentCat ? 'active' : '';
    btn.addEventListener('click', ()=>{ currentCat = c.id; renderTabs(); renderGrid(); });
    catTabsEl.appendChild(btn);
  });
}

function renderGrid(){
  soundGridEl.innerHTML = '';
  PRESETS.filter(p=>p.cat === currentCat).forEach(preset=>{
    const btn = document.createElement('div');
    btn.className = 'sound-btn' + (activeSounds.has(preset.id) ? ' active' : '');
    btn.innerHTML = `<span class="icon">${preset.icon}</span><span><div class="label">${preset.label}</div><div class="sub">${preset.sub}</div></span>`;
    btn.addEventListener('click', async ()=>{
      await ensureAudioContext();
      if (activeSounds.has(preset.id)){
        activeSounds.get(preset.id).stop();
        activeSounds.delete(preset.id);
        btn.classList.remove('active');
      } else {
        const handle = preset.build(ambientBus);
        activeSounds.set(preset.id, handle);
        btn.classList.add('active');
      }
      updateTint();
    });
    soundGridEl.appendChild(btn);
  });
}
renderTabs(); renderGrid();

function updateTint(){
  if (activeSounds.size === 0){
    document.documentElement.style.setProperty('--tint', '5,7,13');
    return;
  }
  let r=0,g=0,b=0,n=0;
  activeSounds.forEach((_,id)=>{
    const p = PRESETS.find(x=>x.id===id);
    if (!p) return;
    const [pr,pg,pb] = p.tint.split(',').map(Number);
    r+=pr; g+=pg; b+=pb; n++;
  });
  if (n>0) document.documentElement.style.setProperty('--tint', `${Math.round(r/n)},${Math.round(g/n)},${Math.round(b/n)}`);
}

function activeVisualKinds(){
  const s = new Set();
  activeSounds.forEach((_,id)=>{ const p = PRESETS.find(x=>x.id===id); if (p) s.add(p.visual); });
  return s;
}

/* =========================================================
   CANVAS VISUALIZER + PARTICLES
========================================================= */
const canvas = document.getElementById('stage');
const ctx = canvas.getContext('2d');
const flashEl = document.getElementById('flash');
let W, H, DPR;
function resize(){
  DPR = Math.min(window.devicePixelRatio || 1, 2);
  W = window.innerWidth; H = window.innerHeight;
  canvas.width = W*DPR; canvas.height = H*DPR;
  canvas.style.width = W+'px'; canvas.style.height = H+'px';
  ctx.setTransform(DPR,0,0,DPR,0,0);
}
window.addEventListener('resize', resize); resize();

let particles = [];
function spawn(kind, opts={}){
  particles.push(Object.assign({ kind, life:1 }, opts));
}

function emitContinuous(kinds){
  if (kinds.has('rain')){
    for(let i=0;i<3;i++) if (Math.random()<0.9) spawn('rain', { x:Math.random()*W, y:-10, vy:9+Math.random()*4, len:14+Math.random()*14, life:1 });
  }
  if (kinds.has('snow')){
    if (Math.random()<0.6) spawn('snow', { x:Math.random()*W, y:-10, vy:0.6+Math.random()*0.8, sway:Math.random()*2, size:1.5+Math.random()*2.5, life:1, t:Math.random()*10 });
  }
  if (kinds.has('leaf')){
    if (Math.random()<0.35) spawn('leaf', { x:Math.random()*W, y:-10, vy:0.6+Math.random()*0.6, sway:1+Math.random()*2, rot:Math.random()*Math.PI, vrot:(Math.random()-0.5)*0.05, size:4+Math.random()*3, life:1, t:Math.random()*10 });
  }
  if (kinds.has('bubble')){
    if (Math.random()<0.4) spawn('bubble', { x:Math.random()*W, y:H+10, vy:-(0.6+Math.random()*0.8), size:1.5+Math.random()*3, life:1 });
  }
  if (kinds.has('wind')){
    if (Math.random()<0.25) spawn('wind', { x:-20, y:Math.random()*H*0.7+H*0.05, vx:3+Math.random()*4, len:60+Math.random()*80, life:1 });
  }
  if (kinds.has('star') && particles.filter(p=>p.kind==='star').length < 90){
    if (Math.random()<0.3) spawn('star', { x:Math.random()*W, y:Math.random()*H*0.7, size:0.6+Math.random()*1.4, life:1, phase:Math.random()*Math.PI*2, decay:0 });
  }
  if (kinds.has('firefly') && particles.filter(p=>p.kind==='firefly').length < 24){
    if (Math.random()<0.15) spawn('firefly', { x:Math.random()*W, y:H*0.4+Math.random()*H*0.5, vx:(Math.random()-0.5)*0.4, vy:(Math.random()-0.5)*0.4, size:2+Math.random()*1.5, life:1, phase:Math.random()*Math.PI*2 });
  }
  if (kinds.has('insect') && particles.filter(p=>p.kind==='insect').length < 40){
    if (Math.random()<0.3) spawn('insect', { x:Math.random()*W, y:H*0.6+Math.random()*H*0.35, vx:0, vy:0, size:1+Math.random()*1.2, life:1, jt:Math.random()*10 });
  }
}

function handleEvents(){
  while (visualEvents.length){
    const ev = visualEvents.shift();
    if (ev.kind === 'bird'){
      spawn('bird', { x:-20, y:H*0.15+Math.random()*H*0.35, vx:2.5+Math.random()*2.5, amp:10+Math.random()*14, t:0, life:1 });
    } else if (ev.kind === 'thunder'){
      flashEl.style.transition = 'none';
      flashEl.style.opacity = String(0.35 + Math.random()*0.3);
      requestAnimationFrame(()=>{ flashEl.style.transition = 'opacity 0.9s ease'; flashEl.style.opacity = '0'; });
    } else if (ev.kind === 'leaf'){
      spawn('leaf', { x:Math.random()*W, y:Math.random()*H*0.5, vy:0.8, sway:2, rot:Math.random()*Math.PI, vrot:(Math.random()-0.5)*0.08, size:5+Math.random()*3, life:1, t:0 });
    } else if (ev.kind === 'star'){
      spawn('spark', { x:Math.random()*W, y:Math.random()*H*0.5, life:1, size:2 });
    }
  }
}

function updateAndDrawParticles(dt){
  ctx.globalCompositeOperation = 'lighter';
  particles.forEach(p=>{
    switch(p.kind){
      case 'rain':
        p.y += p.vy; p.life -= 0.004;
        ctx.strokeStyle = 'rgba(180,210,255,0.5)'; ctx.lineWidth = 1;
        ctx.beginPath(); ctx.moveTo(p.x,p.y); ctx.lineTo(p.x-2,p.y+p.len); ctx.stroke();
        if (p.y > H) p.life = 0;
        break;
      case 'snow':
        p.t += 0.02; p.y += p.vy; p.x += Math.sin(p.t)*p.sway*0.05; p.life -= 0.0025;
        ctx.fillStyle = 'rgba(255,255,255,0.85)';
        ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.fill();
        if (p.y > H) p.life = 0;
        break;
      case 'leaf':
        p.t += 0.02; p.y += p.vy; p.x += Math.sin(p.t)*p.sway*0.3; p.rot += p.vrot; p.life -= 0.003;
        ctx.save(); ctx.translate(p.x,p.y); ctx.rotate(p.rot);
        ctx.fillStyle = 'rgba(140,200,120,0.7)';
        ctx.beginPath(); ctx.ellipse(0,0,p.size,p.size*0.5,0,0,Math.PI*2); ctx.fill();
        ctx.restore();
        if (p.y > H) p.life = 0;
        break;
      case 'bubble':
        p.y += p.vy; p.life -= 0.006;
        ctx.strokeStyle = 'rgba(150,230,255,0.5)'; ctx.lineWidth = 1;
        ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.stroke();
        if (p.y < -10) p.life = 0;
        break;
      case 'wind':
        p.x += p.vx; p.life -= 0.01;
        ctx.strokeStyle = `rgba(210,230,255,${0.18*p.life})`; ctx.lineWidth = 1;
        ctx.beginPath(); ctx.moveTo(p.x,p.y); ctx.lineTo(p.x+p.len,p.y); ctx.stroke();
        if (p.x > W+100) p.life = 0;
        break;
      case 'star':
        p.phase += 0.03;
        ctx.fillStyle = `rgba(255,255,255,${0.4+0.5*Math.sin(p.phase)})`;
        ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.fill();
        break;
      case 'firefly':
        p.phase += 0.05; p.x += p.vx; p.y += p.vy;
        ctx.fillStyle = `rgba(255,230,120,${0.3+0.5*Math.sin(p.phase)})`;
        ctx.shadowBlur = 10; ctx.shadowColor = '#ffe678';
        ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.fill();
        ctx.shadowBlur = 0;
        break;
      case 'insect':
        p.jt += 0.15; p.x += Math.sin(p.jt*2)*0.6; p.y += Math.cos(p.jt*1.7)*0.4;
        ctx.fillStyle = 'rgba(200,255,150,0.35)';
        ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.fill();
        break;
      case 'bird':
        p.t += 0.05; p.x += p.vx; p.y += Math.sin(p.t*2)*0.6; p.life -= 0.006;
        ctx.strokeStyle = 'rgba(230,240,255,0.7)'; ctx.lineWidth = 1.4;
        ctx.beginPath();
        ctx.moveTo(p.x-6,p.y+Math.sin(p.t*6)*3);
        ctx.quadraticCurveTo(p.x, p.y-4, p.x+6, p.y+Math.sin(p.t*6)*3);
        ctx.stroke();
        if (p.x > W+30) p.life = 0;
        break;
      case 'spark':
        p.life -= 0.02;
        ctx.fillStyle = `rgba(255,255,255,${p.life})`;
        ctx.beginPath(); ctx.arc(p.x,p.y,p.size,0,Math.PI*2); ctx.fill();
        break;
    }
  });
  particles = particles.filter(p => p.life === undefined || p.life > 0);
  ctx.globalCompositeOperation = 'source-over';
}

/* waveform line (reacts to real combined ambient audio) */
function getSamples(){
  if (waveform){ try { return waveform.getValue(); } catch(e){} }
  return null;
}
function drawWaveLayer(samples, opts){
  const { blur, alpha, lineWidth, ampMul, color } = opts;
  const n = samples.length;
  const midY = H*0.82;
  const t = performance.now()/1000;
  ctx.beginPath();
  for (let i=0;i<n;i++){
    const x = (i/(n-1)) * W;
    const phase = Math.sin(i*0.04 + t*waveSpeed*0.6) * 1.4;
    const v = samples[i] * ampScale * ampMul;
    const y = midY - v * (H*0.16) + phase;
    if (i===0) ctx.moveTo(x,y); else ctx.lineTo(x,y);
  }
  ctx.lineWidth = lineWidth; ctx.strokeStyle = color; ctx.globalAlpha = alpha;
  ctx.shadowBlur = blur; ctx.shadowColor = color;
  ctx.stroke(); ctx.globalAlpha = 1; ctx.shadowBlur = 0;
}
let idleT = 0;
function drawIdleWave(){
  idleT += 0.015;
  const n = 200; const midY = H*0.82;
  ctx.beginPath();
  for (let i=0;i<n;i++){
    const x = (i/(n-1))*W;
    const y = midY + Math.sin(i*0.15 + idleT) * 6 * Math.sin(idleT*0.5);
    if (i===0) ctx.moveTo(x,y); else ctx.lineTo(x,y);
  }
  ctx.lineWidth = 1.2; ctx.strokeStyle = currentTheme.accent; ctx.globalAlpha = 0.2;
  ctx.shadowBlur = 12; ctx.shadowColor = currentTheme.accent;
  ctx.stroke(); ctx.globalAlpha = 1; ctx.shadowBlur = 0;
}

function frame(){
  ctx.clearRect(0,0,W,H);
  const grad = ctx.createRadialGradient(W/2,H*0.4,0,W/2,H*0.4,Math.max(W,H)*0.7);
  grad.addColorStop(0,'rgba(255,255,255,0.03)'); grad.addColorStop(1,'rgba(0,0,0,0)');
  ctx.fillStyle = grad; ctx.fillRect(0,0,W,H);

  const kinds = activeVisualKinds();
  handleEvents();
  if (activeSounds.size > 0) emitContinuous(kinds);
  updateAndDrawParticles();

  const samples = getSamples();
  if (samples && (activeSounds.size > 0 || dronePlaying)){
    drawWaveLayer(samples, { blur:34, alpha:0.22, lineWidth:7, ampMul:1.05, color: currentTheme.accent2 });
    drawWaveLayer(samples, { blur:22, alpha:0.32, lineWidth:3.5, ampMul:1.0, color: currentTheme.accent });
    drawWaveLayer(samples, { blur:10, alpha:0.9, lineWidth:1.4, ampMul:1.0, color: '#ffffff' });
  } else {
    drawIdleWave();
  }
  requestAnimationFrame(frame);
}
requestAnimationFrame(frame);
applyTheme('cyan');
</script>
@stack('scripts')

</body>
</html>