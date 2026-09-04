// ====================================================
// 2. ระบบ Modal เครื่องเล่นเพลง (Global)
// ห่อด้วย IIFE เพื่อกันตัวแปรชนกับสคริปต์ไฟล์อื่น (เช่น currentCat
// ที่ชนกับไฟล์ category page) — ฟังก์ชันที่ไฟล์อื่นต้องเรียกใช้
// (openPlayerModal, closePlayerModal) จะแปะไว้ที่ window เอง
// ====================================================

    const playerModal = document.getElementById("musicPlayerModal");
    const mainAudio = document.getElementById("mainAudioPlayer");
    const playerTitle = document.getElementById("playerSongName");
    const playerImg = document.getElementById("playerImage");

    let currentActiveButton = null;
    let currentActiveCard = null;

    // ====================================================
    // 2.0 ระบบแถบเลื่อนเวลาเพลง (seek bar)
    // ====================================================
    const seekBar = document.getElementById("seekBar");
    const currentTimeLabel = document.getElementById("currentTimeLabel");
    const durationLabel = document.getElementById("durationLabel");

    // แปลงวินาที (number) เป็นรูปแบบ m:ss เช่น 125 -> "2:05"
    function formatTime(seconds) {
        if (!isFinite(seconds) || seconds < 0) return "0:00";
        const m = Math.floor(seconds / 60);
        const s = Math.floor(seconds % 60);
        return `${m}:${String(s).padStart(2, "0")}`;
    }

    // ผู้ใช้กำลังลาก seek bar อยู่ไหม — ถ้าใช่ ต้องหยุดอัปเดตค่า
    // จาก timeupdate ชั่วคราว ไม่งั้นแถบจะกระตุกกลับตำแหน่งเดิมขณะลาก
    let isSeeking = false;

    function initSeekBar() {
        if (!seekBar || !mainAudio) {
            return;
        }

        mainAudio.addEventListener("loadedmetadata", () => {
            seekBar.max = mainAudio.duration || 0;
            if (durationLabel) durationLabel.textContent = formatTime(mainAudio.duration);
        });

        mainAudio.addEventListener("timeupdate", () => {
            if (isSeeking) return;
            seekBar.value = mainAudio.currentTime;
            if (currentTimeLabel) currentTimeLabel.textContent = formatTime(mainAudio.currentTime);
        });

        seekBar.addEventListener("mousedown", () => { isSeeking = true; });
        seekBar.addEventListener("touchstart", () => { isSeeking = true; });

        seekBar.addEventListener("input", () => {
            if (currentTimeLabel) currentTimeLabel.textContent = formatTime(Number(seekBar.value));
        });

        seekBar.addEventListener("change", () => {
            mainAudio.currentTime = Number(seekBar.value);
            isSeeking = false;
        });

        mainAudio.addEventListener("ended", () => {
            seekBar.value = 0;
            if (currentTimeLabel) currentTimeLabel.textContent = "0:00";
        });
    }

    initSeekBar();

    // ตั้งชื่อให้ชัดเจนไม่ชนกับตัวแปร currentCat ในไฟล์ category page
    let currentPlayingCat = null;

    // ====================================================
    // 2.1 ระบบเปลี่ยนสีพื้นหลังตามหมวดหมู่ (mood) ของเพลงที่กำลังเล่น
    // ====================================================
    const MOOD_THEMES = ['bg-focus', 'bg-mood', 'bg-relax', 'bg-sleep', 'bg-stress'];
    const MOOD_COLOR = {เพิ่มสมาธิและโฟกัส:'bg-focus',ปรับอารมณ์ให้ดีขึ้น:'bg-mood',ผ่อนคลายทั่วไป:'bg-relax', นอนหลับ:'bg-sleep', ลดความเครียด:'bg-stress'};

    function setMoodTheme(cat) {
        document.body.classList.remove(...MOOD_THEMES);
        if (!cat) return;
        const themeClass = MOOD_COLOR[cat].toLowerCase();
        if (MOOD_THEMES.includes(themeClass)) {
            document.body.classList.add(themeClass);
        }
    }

    function setBgImage(el, imgUrl) {
        if (!el) return;
        el.style.backgroundImage = (imgUrl && imgUrl !== 'none') ? `url('${imgUrl}')` : 'none';
    }

    // ====================================================
    // 2.2 ระบบเล่นเพลงต่อเนื่องอัตโนมัติ + เลือกเพลงถัดไปเอง
    // ====================================================
    window.songPoolByCategory = window.songPoolByCategory || {};
    let currentPlaylist = null;
    let currentPlaylistIndex = -1;

    const CATEGORY_ORDER = ['เพิ่มสมาธิ/โฟกัส', 'ปรับอารมณ์ให้ดีขึ้น', 'ผ่อนคลายทั่วไป', 'นอนหลับ', 'ลดความเครียด'];

    let autoplayTimer = null;
    let autoplayCountdownInterval = null;
    let nextUpEl = null;

    if (playerModal) {
        nextUpEl = document.createElement('div');
        nextUpEl.id = 'nextUpPanel';
        nextUpEl.className = 'next-up-panel';
        nextUpEl.style.pointerEvents = 'none';
        playerModal.appendChild(nextUpEl);
    }

    function cancelAutoplay() {
        if (autoplayTimer) clearTimeout(autoplayTimer);
        if (autoplayCountdownInterval) clearInterval(autoplayCountdownInterval);
        autoplayTimer = null;
        autoplayCountdownInterval = null;
        if (nextUpEl) {
            nextUpEl.classList.remove('show');
            nextUpEl.style.pointerEvents = 'none';
            nextUpEl.innerHTML = '';
        }
    }

    function getRecommendedNext() {
        if (currentPlaylist && currentPlaylist.length > 0) {
            const nextIndex = currentPlaylistIndex + 1;
            if (nextIndex < currentPlaylist.length) {
                return { queue: currentPlaylist, index: nextIndex, song: currentPlaylist[nextIndex] };
            }
        }
        const pool = (currentPlayingCat && window.songPoolByCategory[currentPlayingCat]) ? window.songPoolByCategory[currentPlayingCat] : [];
        if (pool.length > 0) {
            const currentSong = (currentPlaylist && currentPlaylistIndex >= 0) ? currentPlaylist[currentPlaylistIndex] : null;
            let candidates = pool;
            if (currentSong && pool.length > 1) candidates = pool.filter(s => s.id !== currentSong.id);
            const randomSong = candidates[Math.floor(Math.random() * candidates.length)];
            return { queue: pool, index: pool.indexOf(randomSong), song: randomSong };
        }
        return null;
    }

    function buildNextUpChoices() {
        const recommended = getRecommendedNext();
        const recCat = recommended ? String(recommended.song.cat || currentPlayingCat || '') : null;

        const slots = [];
        CATEGORY_ORDER.forEach(cat => {
            const pool = window.songPoolByCategory[cat] || [];
            if (pool.length === 0) return;

            if (recommended && cat === recCat) {
                slots.push({ cat, song: recommended.song, queue: recommended.queue, index: recommended.index, isDefault: true });
            } else {
                const song = pool[Math.floor(Math.random() * pool.length)];
                slots.push({ cat, song, queue: null, index: -1, isDefault: false });
            }
        });

        if (recommended && slots.length > 0 && !slots.some(s => s.isDefault)) {
            slots[0] = { ...slots[0], song: recommended.song, queue: recommended.queue, index: recommended.index, isDefault: true };
        }
        return slots;
    }

    function renderNextUpChoices() {
        if (!nextUpEl) return null;
        const slots = buildNextUpChoices();
        if (slots.length === 0) return null;

        nextUpEl.innerHTML = `
            <div class="next-up-header">
                <span>🎧 เลือกเพลงถัดไป (สุ่มมาให้หมวดละ 1 เพลง)</span>
                <button type="button" id="dismissNextUpBtn" title="ปิด">&times;</button>
            </div>
            <div class="next-up-row"></div>
        `;
        const row = nextUpEl.querySelector('.next-up-row');
        let defaultSlot = null;

        slots.forEach(slot => {
            const cfg = CAT_CONFIG[slot.cat] || { label: slot.cat, icon: '🎵' };
            const imgUrl = slot.song.image ? `/image/${slot.song.image}` : 'none';

            const card = document.createElement('button');
            card.type = 'button';
            card.className = 'next-up-card' + (slot.isDefault ? ' is-default' : '');
            setBgImage(card, imgUrl);
            card.innerHTML = `
                <span class="next-up-cat">${cfg.icon} ${cfg.label}</span>
                <span class="next-up-name">${slot.song.musicname}</span>
                ${slot.isDefault ? '<span class="next-up-timer" id="nextUpTimerNum">5</span>' : ''}
            `;
            card.addEventListener('click', () => {
                cancelAutoplay();
                playQueueSong(slot.queue, slot.index, slot.song);
            });
            row.appendChild(card);

            if (slot.isDefault) defaultSlot = { el: card, ...slot };
        });

        nextUpEl.querySelector('#dismissNextUpBtn')?.addEventListener('click', (e) => {
            e.stopPropagation();
            cancelAutoplay();
            setMoodTheme(null);
        });

        nextUpEl.classList.add('show');
        nextUpEl.style.pointerEvents = 'auto';
        return defaultSlot;
    }

    function startAutoplayCountdown(seconds, defaultSlot, onDone) {
        if (!defaultSlot) { onDone(); return; }
        let remaining = seconds;
        const timerEl = defaultSlot.el.querySelector('#nextUpTimerNum');
        if (timerEl) timerEl.textContent = remaining;

        autoplayCountdownInterval = setInterval(() => {
            remaining -= 1;
            if (timerEl) timerEl.textContent = Math.max(remaining, 0);
        }, 1000);

        autoplayTimer = setTimeout(() => {
            cancelAutoplay();
            onDone();
        }, seconds * 1000);
    }

    function playQueueSong(queue, index, song) {
        if (!song) return;
        const imgUrl = song.image ? `/image/${song.image}` : "none";
        // ★ แก้แล้ว: เพิ่ม song.id เป็น argument ตัวสุดท้าย
        // กันเคส queue เป็น null (การ์ด next-up ที่ไม่ใช่ default) ไม่ให้ musicid หลุดเป็น null
        window.openPlayerModal(song.musicname, song.musicfile, imgUrl, null, null, song.cat, queue, index, song.id);
    }

    function openPlayerModal(songName, fileUrl, imgUrl, button = null, container = null, cat = null, queue = null, queueIndex = -1, musicId = null) {
        if (!playerModal || !mainAudio) return;

        cancelAutoplay();

        const resolvedMusicId = musicId ?? (queue && queue[queueIndex] ? queue[queueIndex].id : null);
        startTracking(resolvedMusicId);

        playerTitle.innerText = songName;
        setBgImage(playerImg, imgUrl);
        const fullUrl = fileUrl ? `/audio/${fileUrl}` : "none";
        mainAudio.src = fullUrl;

        playerModal.classList.add("show");
        mainAudio.play();

        setMoodTheme(cat);

        currentPlayingCat = cat || currentPlayingCat;
        currentPlaylist = (queue && queue.length) ? queue : null;
        currentPlaylistIndex = currentPlaylist ? queueIndex : -1;

        if (currentActiveButton) currentActiveButton.querySelector("i").className = "fa-solid fa-play";
        if (currentActiveCard) currentActiveCard.classList.remove("playing");

        if (button) button.querySelector("i").className = "fa-solid fa-pause";
        if (container) container.classList.add("playing");

        currentActiveButton = button;
        currentActiveCard = container;
    }

    window.openPlayerModal = openPlayerModal;

    window.closePlayerModal = function () {
        cancelAutoplay();
        finalizeCurrentTracking();
        if (mainAudio) mainAudio.pause();
        if (playerModal) playerModal.classList.remove("show");
        if (currentActiveButton) currentActiveButton.querySelector("i").className = "fa-solid fa-play";
        if (currentActiveCard) currentActiveCard.classList.remove("playing");
        setMoodTheme(null);
    };

    if (mainAudio) {
        mainAudio.addEventListener("ended", () => {
            if (currentActiveButton) currentActiveButton.querySelector("i").className = "fa-solid fa-play";
            if (currentActiveCard) currentActiveCard.classList.remove("playing");

            const defaultSlot = renderNextUpChoices();
            if (!defaultSlot) {
                setMoodTheme(null);
                return;
            }
            startAutoplayCountdown(5, defaultSlot, () => {
                playQueueSong(defaultSlot.queue, defaultSlot.index, defaultSlot.song);
            });
        });
    }

    // ====================================================
    // 2.3 ระบบบันทึกประวัติการฟังเพลง (listening history)
    // ส่ง musicid + play_duration (วินาทีที่ "เล่นจริง" ไม่นับช่วง pause)
    // ไปที่ POST /history/save เมื่อเพลงจบ, เปลี่ยนเพลง, ปิด modal,
    // หรือปิด/ออกจากหน้าเว็บกลางคัน
    // ====================================================
    const HISTORY_SAVE_URL =
        document.getElementById("app")?.dataset?.historySaveUrl || "/history/save";
    const MIN_TRACK_SECONDS = 1;

    let trackingMusicId = null;
    let accumulatedMs = 0;
    let segmentStartTs = null;

    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.content || "";
    }

    function flushSegment() {
        if (segmentStartTs !== null) {
            accumulatedMs += Date.now() - segmentStartTs;
            segmentStartTs = null;
        }
    }

    function resumeSegment() {
        if (segmentStartTs === null) {
            segmentStartTs = Date.now();
        }
    }

    function sendHistory(musicId, playDurationSeconds, useBeacon = false) {
        if (!musicId || playDurationSeconds < MIN_TRACK_SECONDS) return;

        const payload = {
            musicid: musicId,
            play_duration: Math.round(playDurationSeconds),
        };

        console.log("[history/save] payload ที่กำลังจะส่ง:", payload); // ← debug ชั่วคราว ลบทิ้งได้เมื่อทดสอบผ่านแล้ว

        if (useBeacon && navigator.sendBeacon) {
            const blob = new Blob([JSON.stringify(payload)], { type: "application/json" });
            navigator.sendBeacon(HISTORY_SAVE_URL, blob);
            return;
        }

        fetch(HISTORY_SAVE_URL, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": getCsrfToken(),
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify(payload),
            keepalive: true,
        }).catch((err) => {
            console.error("บันทึกประวัติการฟังไม่สำเร็จ:", err);
        });
    }

    function finalizeCurrentTracking(useBeacon = false) {
        flushSegment();
        if (trackingMusicId && accumulatedMs > 0) {
            sendHistory(trackingMusicId, accumulatedMs / 1000, useBeacon);
        }
        trackingMusicId = null;
        accumulatedMs = 0;
        segmentStartTs = null;
    }

    function startTracking(musicId) {
        finalizeCurrentTracking();
        trackingMusicId = musicId;
        if (mainAudio && !mainAudio.paused) {
            resumeSegment();
        }
    }

    if (mainAudio) {
        mainAudio.addEventListener("play", resumeSegment);
        mainAudio.addEventListener("pause", flushSegment);
        mainAudio.addEventListener("ended", () => finalizeCurrentTracking(false));
    }

    window.addEventListener("beforeunload", () => finalizeCurrentTracking(true));
    document.addEventListener("visibilitychange", () => {
        if (document.visibilityState === "hidden") {
            finalizeCurrentTracking(true);
        }
    });
