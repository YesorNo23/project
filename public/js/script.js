document.addEventListener("DOMContentLoaded", async function() {

    // ====================================================
    // 1. เช็คว่าผู้ใช้เปิดหน้าไหนอยู่
    // ====================================================
    const isMainPage = document.getElementById("mainSliderTrack") !== null;
    const isCategoryPage = document.getElementById("heroCard") !== null;

    // การกำหนด Path สำหรับ Fetch (เพื่อแก้ปัญหาที่ไฟล์อยู่คนละโฟลเดอร์)
    const fetchSongsUrl = '/get_songs';

    
    // ====================================================
    // 3. ระบบประเมินอารมณ์ & AI แนะนำเพลง (Global)
    // ====================================================
    const assessBtn = document.getElementById("assessBtn");
    const moodModal = document.getElementById("moodModal");
    const closeModalBtn = document.getElementById("closeModalBtn");
    const submitMoodBtn = document.getElementById("submitMoodBtn");


    if (assessBtn) assessBtn.addEventListener("click", () => moodModal?.classList.add("show"));
    if (closeModalBtn) closeModalBtn.addEventListener("click", () => moodModal?.classList.remove("show"));

    window.addEventListener("click", (event) => {
        if (event.target === playerModal) closePlayerModal();
        if (event.target === moodModal) moodModal.classList.remove("show");
    });

    if (submitMoodBtn) {
        submitMoodBtn.addEventListener("click", async () => {
            const getVal = (name) => document.querySelector(`input[name="${name}"]:checked`)?.value || null;
            const answers = { q1: getVal('q1'), q2: getVal('q2'), q3: getVal('q3'), q4: getVal('q4'), q5: getVal('q5') };

            if (Object.values(answers).some(v => !v)) {
                alert('กรุณาตอบคำถามให้ครบทุกข้อก่อนนะคะ 😊'); return;
            }

            submitMoodBtn.textContent = 'กำลังวิเคราะห์...'; 
            submitMoodBtn.disabled = true;

            try {
                const res = await fetch('/api/predict', {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' }, 
                    body: JSON.stringify(answers)
                });
                const result = await res.json();
                
                // 1. ตรวจสอบข้อผิดพลาดจาก Backend
                if (result.error) throw new Error(result.error);

                // 2. ปิดหน้าต่างแบบประเมิน
                moodModal.classList.remove("show");

                // 3. แสดงค่า % ความมั่นใจที่คำนวณจาก Random Forest ออกทาง Console
                console.log(`🤖 AI ประเมินหมวด: ${result.category} | ความมั่นใจ: ${result.confidence}%`);

                // 4. ดึงคลังเพลงในหมวดหมู่ที่ AI ประเมินได้
                const catlist = {focus:'เพิ่มสมาธิและโฟกัส',
                    mood:'ปรับอารมณ์ให้ดีขึ้น',
                    relax:'ผ่อนคลายทั่วไป',
                    sleep:'นอนหลับ',
                    stress:'ลดความเครียด'};
                const cat = String(result.category).toLowerCase();
                const pool = songPoolByCategory[catlist[cat]] || [];

                if (pool.length > 0) {
                    // สุ่มเลือก 1 เพลงจากหมวดนั้น
                    const randomIndex = Math.floor(Math.random() * pool.length);
                    const song = pool[randomIndex];
                    const imgUrl = song.image ? `/image/${song.image}` : "none";

                    // เปิดป็อปอัพเครื่องเล่นและสั่งให้เพลงเริ่มเล่นทันที
                    openPlayerModal(song.musicname, song.musicfile, imgUrl, null, null, catlist[cat], pool, randomIndex);
                } else {
                    alert(`AI แนะนำหมวด ${result.category} (ความมั่นใจ ${result.confidence}%) แต่ยังไม่มีไฟล์เพลงในระบบ`);
                }

            } catch (err) {
                alert('เกิดข้อผิดพลาด: ' + err.message);
            } finally {
                submitMoodBtn.textContent = 'บันทึกข้อมูล'; 
                submitMoodBtn.disabled = false;
            }
        });
    }

    window.CAT_CONFIG = {
        เพิ่มสมาธิและโฟกัส: { label: 'Focus', icon: '🧠', color: '#4A90D9', desc: 'ช่วยให้จดจ่อและมีสมาธิมากขึ้น' },
        ปรับอารมณ์ให้ดีขึ้น: { label: 'Mood', icon: '😊', color: '#E91E8C', desc: 'ปรับอารมณ์ให้แจ่มใสขึ้น' },
        ผ่อนคลายทั่วไป: { label: 'Relax', icon: '🍃', color: '#2E7D32', desc: 'ผ่อนคลายกล้ามเนื้อและความคิด' },
        นอนหลับ: { label: 'Sleep', icon: '🌙', color: '#1565C0', desc: 'กล่อมให้หลับสบายตลอดคืน' },
        ลดความเครียด: { label: 'Stress', icon: '💆', color: '#C62828', desc: 'คลายความเครียดหลังวันที่เหนื่อยล้า' }
    };

    function showRecommendation(result) {
        document.getElementById('ai-result-popup')?.remove();
        const cat = result.category;
        const cfg = CAT_CONFIG[cat.toLowerCase()] || { icon: '🎵', color: '#333' };
        
        const popup = document.createElement('div');
        popup.id = 'ai-result-popup';
        popup.style.cssText = `position: fixed; bottom: 24px; right: 24px; z-index: 9999; background: #fff; border-radius: 16px; padding: 20px 22px; box-shadow: 0 8px 32px rgba(0,0,0,0.15); width: 270px;`;
        popup.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                <span style="font-size:13px;font-weight:600;color:#333;">🤖 AI แนะนำ</span>
                <button onclick="this.parentElement.parentElement.remove()" style="background:none;border:none;font-size:18px;cursor:pointer;color:#aaa;">×</button>
            </div>
            <div style="text-align:center;margin-bottom:14px;">
                <div style="font-size:32px;">${cfg.icon}</div>
                <div style="font-size:18px;font-weight:600;color:${cfg.color};margin:4px 0;">${cat}</div>
                <div style="font-size:12px;color:#888;">ความมั่นใจ ${(result.confidence * 100).toFixed(1)}%</div>
            </div>
            <a href="/test/type/${cat.toLowerCase()}.php" style="display:block;text-align:center;padding:9px;background:${cfg.color}; color:#fff;border-radius:10px;font-size:13px;text-decoration:none;">ฟังเพลง ${cat} เลย →</a>
        `;
        document.body.appendChild(popup);
        setTimeout(() => popup.remove(), 15000);
    }

    // ====================================================
    // 4. ระบบเฉพาะ "หน้าหมวดหมู่"
    // ====================================================
    if (isCategoryPage) {
        let categorySongs = [];
        const catlist = {focus:'เพิ่มสมาธิและโฟกัส',
                    mood:'ปรับอารมณ์ให้ดีขึ้น',
                    relax:'ผ่อนคลายทั่วไป',
                    sleep:'นอนหลับ',
                    stress:'ลดความเครียด'};
        
        // ดึงชื่อหมวดจาก URL (เช่น ไฟล์ชื่อ focus.php จะได้คำว่า focus)
        const pathName = window.location.pathname;
        let currentCat = pathName.split('/').filter(Boolean).pop().replace('.php', '').toLowerCase();
        const typetext = document.getElementById('typetext');
        typetext.innerText ='';
        typetext.innerText = currentCat;
        currentCat =  catlist[currentCat];
        
        try {
            const response = await fetch(fetchSongsUrl);
            const grouped = await response.json();
            songPoolByCategory = grouped; // ใช้สำหรับสุ่มเพลงต่อในหมวดเดียวกันตอนเล่นอัตโนมัติ

            // ดึงเฉพาะเพลงในหมวดนั้นๆ ออกมา
            categorySongs = grouped[currentCat] || grouped[currentCat.charAt(0).toUpperCase() + currentCat.slice(1)] || [];
            
            if (categorySongs.length > 0) {
                // สุ่ม 4 เพลงไปโชว์ในกล่องสี่เหลี่ยมด้านบน
                const shuffledForSlider = [...categorySongs].sort(() => 0.5 - Math.random());
                initHeroSlider(shuffledForSlider.slice(0, 4));
                // เอาเพลงทั้งหมดไปเรียงด้านล่าง
                console.log(categorySongs);
                buildCardGrid(categorySongs);
            } else {
                document.getElementById("cardGrid").innerHTML = `<p style="grid-column: 1/-1; color: #888;">ไม่พบเพลงในหมวดหมู่นี้</p>`;
            }
        } catch (err) { console.error("โหลดเพลงหน้าหมวดหมู่ไม่สำเร็จ:", err); }

        // ระบบปุ่มสุ่ม (สุ่มเฉพาะเพลงในหมวดนี้เท่านั้น)
        const randomBtn = document.getElementById("randomBtn");
        if (randomBtn) {
            randomBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (categorySongs.length === 0) return;
                const song = categorySongs[Math.floor(Math.random() * categorySongs.length)];
                const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                const imgUrl = song.image 
                    ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                    : "";
                openPlayerModal(song.musicname, song.musicfile, imgUrl, null, null, song.cat, null, -1, song.id);
            });
        }

        // ฟังก์ชันสร้างกล่องสี่เหลี่ยมสไลเดอร์หมวดหมู่
        function initHeroSlider(songs) {
            let heroIndex = 0;
            const heroImg = document.getElementById("heroImg");
            const heroName = document.getElementById("heroName");
            const heroSub = document.getElementById("heroSub");
            const heroDots = document.getElementById("heroDots");
            const heroPlay = document.getElementById("heroPlay");

            function updateHero(index) {
                const song = songs[index];
                const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                const imgUrl = song.image 
                    ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                    : "";
                const cfg = CAT_CONFIG[song.cat] || { icon: '🎵', desc: 'เพลงบำบัดสำหรับคุณ' };
                
                if(heroImg) heroImg.style.backgroundImage = `url('${imgUrl}')`;
                if(heroName) heroName.innerText = song.musicname;
                if(heroSub) heroSub.innerText = cfg.desc;

                if (heroDots) {
                    heroDots.innerHTML = '';
                    songs.forEach((_, i) => {
                        const dot = document.createElement("div");
                        dot.className = `hdot ${i === index ? 'on' : ''}`;
                        dot.addEventListener("click", () => { heroIndex = i; updateHero(heroIndex); });
                        heroDots.appendChild(dot);
                    });
                }
                
                if (heroPlay) heroPlay.onclick = () => openPlayerModal(song.musicname, song.musicfile, imgUrl, heroPlay, document.getElementById("heroCard"), song.cat, songs, index);
            }

            if (songs.length > 0) updateHero(0);

            document.getElementById("heroPrev")?.addEventListener("click", () => {
                if (songs.length <= 1) return;
                heroIndex = (heroIndex > 0) ? heroIndex - 1 : songs.length - 1; updateHero(heroIndex);
            });
            document.getElementById("heroNext")?.addEventListener("click", () => {
                if (songs.length <= 1) return;
                heroIndex = (heroIndex < songs.length - 1) ? heroIndex + 1 : 0; updateHero(heroIndex);
            });
        }

        // ฟังก์ชันสร้างการ์ดเพลงเรียงกันด้านล่าง
        function buildCardGrid(songs) {
            const cardGrid = document.getElementById("cardGrid");
            if (!cardGrid) return;
            cardGrid.innerHTML = '';

            songs.forEach((song, idx) => {
                const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                const imgUrl = song.image 
                    ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                    : "";
                const card = document.createElement('div');
                card.className = 'mcard';
                card.innerHTML = `
                    <div class="mcard-img" style="background-image: url('${imgUrl}');">
                        <div class="mcard-ov">
                            <button class="mcard-pbtn"><i class="fa-solid fa-play"></i></button>
                        </div>
                    </div>
                    <div class="mcard-info">
                        <div class="mcard-name">${song.musicname}</div>
                        <div class="mcard-sub">${song.cat}</div>
                    </div>
                `;

                const playBtn = card.querySelector(".mcard-pbtn");
                card.addEventListener('click', (e) => {
                    e.preventDefault();
                    openPlayerModal(song.musicname, song.musicfile, imgUrl, playBtn, card, song.cat, songs, idx);
                });

                cardGrid.appendChild(card);
            });
        }
    }

    // ====================================================
    // 5. ระบบเฉพาะ "หน้าหลัก" (user/index.blade.php)
    // ====================================================
    if (isMainPage) {
        let allSongs = [];

        const CAT_SLUG_MAP = {
            "เพิ่มสมาธิและโฟกัส": "focus",
            "ปรับอารมณ์ให้ดีขึ้น": "mood",
            "ผ่อนคลายทั่วไป": "relax",
            "นอนหลับ": "sleep",
            "ลดความเครียด": "stress",
        };

        function toSlug(cat) {
            return CAT_SLUG_MAP[cat] || encodeURIComponent(cat).toLowerCase();
        }

        async function loadSongs() {
            try {
                const res = await fetch(fetchSongsUrl, {
                    method: "GET",
                    headers: {
                        Accept: "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                    },
                });

                if (!res.ok) {
                    throw new Error(`HTTP ${res.status}: ${res.statusText}`);
                }

                const grouped = await res.json();

                allSongs = Object.values(grouped).flat();
                window.songPoolByCategory = grouped;

                const mySliderIds = ["59", "89", "120", "2","139"];
                let selectedSliderSongs = allSongs.filter((song) =>
                    mySliderIds.includes(String(song.id))
                );
                if (selectedSliderSongs.length === 0) {
                    selectedSliderSongs = allSongs.slice(0, 4);
                }

                setupBigSlider(selectedSliderSongs);
                buildCategorySections(grouped);
            } catch (err) {
                console.error("โหลดเพลงไม่สำเร็จ:", err);
            }
        }

        loadSongs();

        function setupBigSlider(songs) {
            const track = document.getElementById("mainSliderTrack");
            if (!track) return;
            track.innerHTML = "";

            songs.forEach((song, index) => {
                const cfg = CAT_CONFIG[song.cat] || {
                    color: "#333",
                    icon: "🎵",
                    desc: "เพลงบำบัดสำหรับคุณ",
                };
                const slideItem = document.createElement("div");
                slideItem.className = "big-slide-item";

                const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                const imgUrl = song.image 
                    ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                    : "";

                slideItem.style.backgroundImage = song.image
                    ? `url('${imgUrl}')`
                    : `linear-gradient(135deg, ${cfg.color}, #111)`;
                slideItem.innerHTML = `
                    <div class="big-slide-info">
                        <h2>${cfg.icon} ${song.musicname}</h2>
                        <p>${cfg.desc}</p>
                    </div>
                    <button class="music-play-btn"><i class="fa-solid fa-play"></i></button>
                `;
                track.appendChild(slideItem);

                slideItem.addEventListener("click", (e) => {
                    if (e.target.closest(".music-play-btn")) {
                        e.preventDefault();
                        const playBtn = slideItem.querySelector(".music-play-btn");
                        openPlayerModal(
                            song.musicname,
                            song.musicfile,
                            imgUrl,
                            playBtn,
                            slideItem,
                            song.cat,
                            songs,
                            index
                        );
                    }
                });
            });
        }

        function buildCategorySections(grouped) {
            const categorySections = document.getElementById("categorySections");
            if (!categorySections) return;
            categorySections.innerHTML = "";
            const order = [
                "เพิ่มสมาธิและโฟกัส",
                "ปรับอารมณ์ให้ดีขึ้น",
                "ผ่อนคลายทั่วไป",
                "นอนหลับ",
                "ลดความเครียด",
            ];

            order.forEach((cat) => {
                const songs = grouped[cat];
                if (!songs || songs.length === 0) return;
                const cfg = CAT_CONFIG[cat] || { label: cat, icon: "🎵", color: "#888" };
                const slug = toSlug(cat); // ← ใช้ slug แทน cat ดิบสำหรับ id/URL

                const section = document.createElement("div");
                section.className = "category-section";
                section.innerHTML = `
                    <div class="category-header">
                        <span class="cat-icon">${cfg.icon}</span>
                        <span class="category-title">${cfg.label}</span>
                        <span class="category-count">${songs.length} เพลง</span>
                        <a href="/music/${slug}" style="margin-left:auto;font-size:13px;color:rgba(7, 7, 7, 0.7);text-decoration:none;">ดูทั้งหมด →</a>
                    </div>
                    <div class="category-slider-wrapper">
                        <button class="cat-arrow-btn cat-prev-btn"><i class="fa-solid fa-chevron-left"></i></button>
                        <div class="category-scroll-container" id="grid-${slug}"></div>
                        <button class="cat-arrow-btn cat-next-btn"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                `;
                categorySections.appendChild(section);

                const grid = section.querySelector(`#grid-${slug}`);

                songs.forEach((song, idx) => {
                    const card = document.createElement("div");
                    card.className = "small-music-card";
                    const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                    const imgUrl = song.image 
                        ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                        : "";
                    card.style.backgroundImage = song.image
                        ? `url('${imgUrl}')`
                        : `linear-gradient(135deg, ${cfg.color}88, ${cfg.color}44)`;
                    card.innerHTML = `
                        <div class="small-card-title">${song.musicname}</div>
                        <button class="music-play-btn"><i class="fa-solid fa-play"></i></button>
                    `;
                    const playBtn = card.querySelector(".music-play-btn");
                    card.addEventListener("click", (e) => {
                        e.preventDefault();
                        openPlayerModal(song.musicname, song.musicfile, imgUrl, playBtn, card, cat, songs, idx);
                    });
                    grid.appendChild(card);
                });

                const prevBtn = section.querySelector(".cat-prev-btn");
                const nextBtn = section.querySelector(".cat-next-btn");

                prevBtn.addEventListener("click", () => {
                    const slideWidth = grid.clientWidth;
                    if (grid.scrollLeft <= 10) {
                        grid.scrollTo({ left: grid.scrollWidth, behavior: "smooth" });
                    } else {
                        grid.scrollBy({ left: -slideWidth, behavior: "smooth" });
                    }
                });

                nextBtn.addEventListener("click", () => {
                    const slideWidth = grid.clientWidth;
                    if (grid.scrollLeft + grid.clientWidth >= grid.scrollWidth - 5) {
                        grid.scrollTo({ left: 0, behavior: "smooth" });
                    } else {
                        grid.scrollBy({ left: slideWidth, behavior: "smooth" });
                    }
                });
            });
        }

        const randomBtn = document.getElementById("randomBtn");
        if (randomBtn) {
            randomBtn.addEventListener("click", function (e) {
                e.preventDefault();
                if (allSongs.length === 0) return;
                const song = allSongs[Math.floor(Math.random() * allSongs.length)];
                // แก้จาก song.img → song.image ให้ตรงกับ field จริงจาก backend
                const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                const imgUrl = song.image 
                    ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                    : "";
                openPlayerModal(song.musicname, song.musicfile, imgUrl, null, null, song.cat, null, -1, song.id);
            });
        }

        let sliderIndex = 0;
        function moveSlider() {
            const track = document.getElementById("mainSliderTrack");
            if (track) track.style.transform = `translateX(${-sliderIndex * 100}%)`;
        }
        function nextSlide() {
            const track = document.getElementById("mainSliderTrack");
            if (!track || track.children.length <= 1) return;
            sliderIndex = sliderIndex < track.children.length - 1 ? sliderIndex + 1 : 0;
            moveSlider();
        }
        function prevSlide() {
            const track = document.getElementById("mainSliderTrack");
            if (!track || track.children.length <= 1) return;
            sliderIndex = sliderIndex > 0 ? sliderIndex - 1 : track.children.length - 1;
            moveSlider();
        }

        setInterval(nextSlide, 5000);
        document.getElementById("prevSlideBtn")?.addEventListener("click", (e) => {
            e.preventDefault();
            prevSlide();
        });
        document.getElementById("nextSlideBtn")?.addEventListener("click", (e) => {
            e.preventDefault();
            nextSlide();
        });
    }

    
    

    // ====================================================
    // 6. ระบบเฉพาะห "น้าประวัติการฟัง"
    // ====================================================
    const isHistoryPage = document.getElementById("historyGroups") !== null;
    if (isHistoryPage) {
        let historyOffset = 0;
        const HISTORY_PAGE_SIZE = 15; // จำนวนเพลงต่อการโหลด 1 ครั้ง

        async function loadHistory(reset = true) {
            try {
                const res = await fetch(`/get_history?offset=${historyOffset}&limit=${HISTORY_PAGE_SIZE}`, {
                    headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" }
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();
                // คาดหวังรูปแบบ: { total: 128, groups: [ { label: "วันนี้", items: [ {id, musicname, image, musicfile, cat, time} ] }, ... ], hasMore: true }

                document.getElementById("historyStats").innerText = `ฟังไปแล้ว ${data.total} เพลงในเดือนนี้`;

                const container = document.getElementById("historyGroups");
                if (reset) container.innerHTML = "";

                data.groups.forEach(group => {
                    const dayBlock = document.createElement("div");

                    const label = document.createElement("div");
                    label.className = "history-day-label";
                    label.innerText = group.label;
                    dayBlock.appendChild(label);

                    const grid = document.createElement("div");
                    grid.className = "history-card-grid";

                    group.items.forEach(song => {
                        const supabaseBaseUrl = "https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/";

                        const imgUrl = song.image 
                            ? (song.image.startsWith('http') ? song.image : `${supabaseBaseUrl}${song.image}`) 
                            : "";
                        const card = document.createElement("div");
                    
                    card.className = 'history-card';
                    card.innerHTML = `
                        <div class="mcard-img" style="background-image: url('${imgUrl}');">
                            <div class="mcard-ov">
                                <button class="mcard-pbtn"><i class="fa-solid fa-play"></i></button>
                            </div>
                        </div>
                        <div class="mcard-info">
                            <div class="mcard-name">${song.musicname}</div>
                            <div class="mcard-sub">${song.cat}</div>
                        </div>
                    `;

                        card.addEventListener("click", () => {
                            openPlayerModal(song.musicname, song.musicfile, imgUrl, null, card, song.cat, group.items, group.items.indexOf(song));
                        });
                        grid.appendChild(card);
                    });

                    dayBlock.appendChild(grid);
                    container.appendChild(dayBlock);
                });

                historyOffset += HISTORY_PAGE_SIZE;
                document.getElementById("loadMoreHistoryBtn").style.display = data.hasMore ? "inline-block" : "none";

            } catch (err) {
                console.error("โหลดประวัติการฟังไม่สำเร็จ:", err);
            }
        }

        loadHistory();

        document.getElementById("loadMoreHistoryBtn")?.addEventListener("click", () => loadHistory(false));
    }

    const isPlaylistPage = document.getElementById("playlistGrid") !== null;

    // ====================================================
    // 7. ระบบเฉพาะ "หน้า PlayList"
    // ====================================================
    if (isPlaylistPage) {

        // สีวนใช้กับแต่ละเพลย์ลิสต์ (ไม่มีรูปจริงก็ยังดูสวยได้)
        const VINYL_COLORS = ["#D4537E", "#8B7BD8", "#8FD66B", "#6BB8D6", "#E8C24A"];

        function colorForIndex(i) {
            return VINYL_COLORS[i % VINYL_COLORS.length];
        }

        async function loadPlaylists() {
        try {
            const res = await fetch('/get_playlists', {
                headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            const playlists = await res.json();
            document.getElementById("playlistCount").innerText = `${playlists.length} เพลย์ลิสต์`;

            const grid = document.getElementById("playlistGrid");
            grid.innerHTML = "";

            playlists.forEach((pl, i) => {
                const color = colorForIndex(i);
                // ใช้ปกที่ตั้งเอง หรือรูปเพลงแรกในเพลย์ลิสต์ ถ้าไม่มีเลยค่อย fallback เป็นสีพื้น
                const coverImg = pl.cover
                    ? `https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/${pl.cover}`
                    : (pl.songs && pl.songs[0] && pl.songs[0].image ? `/image/${pl.songs[0].image}` : "");

                const card = document.createElement("div");
                card.className = "playlist-vinyl-card";
                card.innerHTML = `
                        <div class="playlist-vinyl-stage">
                            <div class="playlist-vinyl-square" style="background:${color};"></div>
                            <div class="playlist-vinyl-cover" style="background-image:url('${coverImg}'); background-color:${color};"></div>
                        </div>
                        <div class="playlist-vinyl-name">${pl.name}</div>
                        <div class="playlist-vinyl-count">${pl.song_count} เพลง</div>
                    `;
                card.addEventListener("click", () => {
                    window.location.href = `/playlist/${pl.id}`;
                });
                grid.appendChild(card);
            });

        } catch (err) {
            console.error("โหลดเพลย์ลิสต์ไม่สำเร็จ:", err);
        }
    }

        loadPlaylists();

        document.getElementById("playlistMoreBtn")?.addEventListener("click", () => {
            // เปิดเมนูตัวเลือกเพิ่มเติม (สร้างเพลย์ลิสต์ใหม่, จัดเรียง ฯลฯ) — ผูก modal ที่ออกแบบไว้ก่อนหน้าได้เลย
            openCreatePlaylistModal();
        });
    }

    const isPlaylistDetailPage = document.getElementById("pldSongRows") !== null;

    // ====================================================
    // 8. ระบบเฉพาะ "หน้า PlayList_Detail"
    // ====================================================
    if (isPlaylistDetailPage) {
        const playlistId = window.currentPlaylistId;
        let playlistSongs = [];

        window.isPlaylistPlayback = true;   // เปิดโหมด playlist
        window.playlistRows = [];           // เก็บ element แต่ละแถวไว้ไฮไลต์

        // เล่นเพลงตาม index ใน playlist (ใช้ร่วมกันทุกปุ่ม)
        function playFromPlaylist(idx, container = null) {
            const song = playlistSongs[idx];
            if (!song) return;
            const imgUrl = song.image ? `https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/${song.image}` : "none";
            openPlayerModal(song.musicname, song.musicfile, imgUrl, null,
                container ?? window.playlistRows[idx] ?? null,
                song.cat, playlistSongs, idx, song.id);
        }

        async function loadPlaylistDetail() {
            try {
                const res = await fetch(`/get_playlist/${playlistId}`, {
                    headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" }
                });
                if (!res.ok) throw new Error(`HTTP ${res.status}`);

                const data = await res.json();
                // คาดหวังรูปแบบ: { id, name, description, cover, songs: [ {id, musicname, musicfile, image, cat, duration} ] }

                playlistSongs = data.songs;

                const coverImg = data.cover ? `/image/${data.cover}` : (data.songs[0] ? `/image/${data.songs[0].image}` : '');
                document.getElementById("pldCover").style.backgroundImage = `url('${coverImg}')`;
                document.getElementById("pldName").innerText = data.name;
                document.getElementById("pldDesc").innerText = data.description || '';

                const totalSec = data.songs.reduce((sum, s) => sum + (s.duration || 0), 0);
                const mins = Math.round(totalSec / 60);
                document.getElementById("pldMeta").innerText = `${data.songs.length} เพลง · ${mins} นาที`;

                const rowsContainer = document.getElementById("pldSongRows");
                rowsContainer.innerHTML = "";

                rowsContainer.innerHTML = "";
                window.playlistRows = [];

                data.songs.forEach((song, idx) => {
                    const imgUrl = song.image ? `https://uitkpjtsgolmupmwzslp.supabase.co/storage/v1/object/public/image/${song.image}` : "none";
                    const row = document.createElement("div");
                    row.className = "pld-song-row";
                    row.innerHTML = `
                        <span class="pld-col-num">${idx + 1}</span>
                        <div class="pld-col-thumb" style="background-image:url('${imgUrl}')"></div>
                        <div class="pld-col-info">
                            <div class="pld-col-name">${song.musicname}</div>
                            <div class="pld-col-sub">${song.cat ?? '-'} &middot; ${formatDuration(song.duration)}</div>
                        </div>
                    `;
                    row.addEventListener("click", () => playFromPlaylist(idx, row));
                    rowsContainer.appendChild(row);
                    window.playlistRows.push(row);
                });
            } catch (err) {
                console.error("โหลดรายละเอียดเพลย์ลิสต์ไม่สำเร็จ:", err);
            }
        }

        function formatDuration(sec) {
            if (!sec) return '--:--';
            const m = Math.floor(sec / 60);
            const s = sec % 60;
            return `${m}:${String(s).padStart(2, '0')}`;
        }

        loadPlaylistDetail();

    document.getElementById("pldPlayBtn")?.addEventListener("click", () => {
        if (playlistSongs.length === 0) return;
        playFromPlaylist(0);
    });

    document.getElementById("pldShuffleBtn")?.addEventListener("click", () => {
        if (playlistSongs.length === 0) return;
        playFromPlaylist(Math.floor(Math.random() * playlistSongs.length));
    });

        document.getElementById("pldAddSongBtn")?.addEventListener("click", () => {
            openCreatePlaylistModal(); // หรือ modal "เพิ่มเพลง" แยกต่างหาก ถ้าต้องการ
        });
    }
});