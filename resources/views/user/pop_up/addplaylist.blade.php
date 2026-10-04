<!--
    ===================================================================
    Add Songs to Playlist — Modal Popup
    เปิดด้วย openAddSongModal(playlistId)
    ใช้ endpoint เดิมที่มีอยู่แล้ว: /get_songs (รายการเพลงทั้งหมด)
                                    /get_playlist/{id} (เช็คว่าเพลงไหนอยู่ในเพลย์ลิสต์แล้ว)
    ===================================================================
-->

<div class="asm-overlay" id="addSongModal">
    <div class="asm-card">
        <span class="asm-close" onclick="closeAddSongModal()">&times;</span>

        <div class="asm-header">
            <h2 class="asm-title">เพิ่มเพลง</h2>
            <p class="asm-subtitle" id="asmPlaylistName">เข้าเพลย์ลิสต์ "..."</p>
        </div>

        <input type="text" id="asmSearchInput" class="asm-search" placeholder="ค้นหาเพลง...">

        <!-- Category filter chips: สร้างอัตโนมัติจาก CAT_CONFIG ที่มีอยู่แล้วในไฟล์ script.js -->
        <div class="asm-filter-row" id="asmFilterRow"></div>

        <div class="asm-song-list" id="asmSongList">
            <!-- รายการเพลงจะถูกสร้างที่นี่โดย JS -->
        </div>

        <div class="asm-selected-count" id="asmSelectedCount">เลือกเพิ่มแล้ว 0 เพลง</div>

        <button type="button" class="asm-btn-add" id="asmAddBtn">เพิ่มเข้าเพลย์ลิสต์</button>
    </div>
</div>

<style>
    .asm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(43, 32, 51, 0.5);
        z-index: 1000000001; /* สูงกว่า edit modal เพราะซ้อนเปิดจากในนั้น */
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .asm-overlay.show { display: flex; }

    .asm-card {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 18px;
        padding: 24px 22px 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        position: relative;
        display: flex;
        flex-direction: column;
        max-height: 80vh;
        box-sizing: border-box;
    }

    .asm-close {
        position: absolute;
        top: 16px;
        right: 18px;
        font-size: 22px;
        color: #b0a8bd;
        cursor: pointer;
        line-height: 1;
    }
    .asm-close:hover { color: #5c5266; }

    .asm-header { margin-bottom: 16px; }
    .asm-title { margin: 0 0 3px; font-size: 18px; font-weight: 700; color: #2b2033; }
    .asm-subtitle { margin: 0; font-size: 12px; color: #8a7f99; }

    .asm-search {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #e5e0ea;
        border-radius: 10px;
        font-size: 13.5px;
        box-sizing: border-box;
        margin-bottom: 10px;
        font-family: inherit;
        outline: none;
    }
    .asm-search:focus { border-color: #D4537E; box-shadow: 0 0 0 3px rgba(212,83,126,0.12); }

    .asm-filter-row {
        display: flex;
        gap: 6px;
        margin-bottom: 14px;
        flex-wrap: wrap;
    }
    .asm-chip {
        font-size: 11px;
        padding: 5px 12px;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #ecd9e2;
        color: #a34d68;
        cursor: pointer;
        white-space: nowrap;
    }
    .asm-chip.active {
        background: #D4537E;
        color: #fff;
        border-color: #D4537E;
        font-weight: 600;
    }

    .asm-song-list {
        flex: 1;
        overflow-y: auto;
        border: 1px solid #f0edf3;
        border-radius: 10px;
        margin-bottom: 14px;
        min-height: 100px;
    }
    .asm-song-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        border-bottom: 1px solid #f5f2f8;
    }
    .asm-song-row:last-child { border-bottom: none; }
    .asm-song-thumb { width: 38px; height: 38px; border-radius: 8px; flex-shrink: 0; background-size: cover; background-position: center; }
    .asm-song-info { flex: 1; min-width: 0; }
    .asm-song-name { font-size: 12.5px; color: #2b2033; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .asm-song-sub { font-size: 10.5px; color: #a34d68; margin-top: 1px; }
    .asm-song-added-tag {
        font-size: 10px;
        font-weight: 700;
        color: #8a7f99;
        background: #f0edf3;
        padding: 3px 8px;
        border-radius: 8px;
        flex-shrink: 0;
    }
    .asm-song-row input[type="checkbox"] { width: 18px; height: 18px; cursor: pointer; flex-shrink: 0; }
    .asm-empty { text-align: center; padding: 24px 12px; font-size: 12.5px; color: #b0a8bd; }

    .asm-selected-count { font-size: 11px; color: #8a7f99; margin-bottom: 10px; }

    .asm-btn-add {
        width: 100%;
        padding: 11px;
        border: none;
        border-radius: 999px;
        background: #D4537E;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }
    .asm-btn-add:hover { background: #993556; }
    .asm-btn-add:disabled { background: #e5c9d4; cursor: not-allowed; }
</style>

<script>
    let asmPlaylistId = null;
    let asmAllSongs = [];
    let asmExistingIds = [];
    let asmSelectedIds = new Set();
    let asmActiveCat = 'all';

    async function openAddSongModal(playlistId) {
        asmPlaylistId = playlistId;
        asmSelectedIds = new Set();
        document.getElementById('addSongModal').classList.add('show');
        document.getElementById('asmSearchInput').value = '';
        asmActiveCat = 'all';

        try {
            // ดึงเพลงทั้งหมด + เพลงที่อยู่ในเพลย์ลิสต์นี้แล้ว พร้อมกัน
            const [songsRes, playlistRes] = await Promise.all([
                fetch('/get_songs', { headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" } }),
                fetch(`/get_playlist/${playlistId}`, { headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" } })
            ]);
            if (!songsRes.ok || !playlistRes.ok) throw new Error('โหลดข้อมูลไม่สำเร็จ');

            const grouped = await songsRes.json(); // { "เพิ่มสมาธิและโฟกัส": [...songs], ... } เหมือนหน้าอื่น
            asmAllSongs = Object.values(grouped).flat();

            const playlistData = await playlistRes.json();
            document.getElementById('asmPlaylistName').innerText = `เข้าเพลย์ลิสต์ "${playlistData.name}"`;
            asmExistingIds = (playlistData.songs || []).map(s => s.id);

            renderAsmFilterChips(Object.keys(grouped));
            renderAsmSongList();
        } catch (err) {
            console.error('เปิดหน้าเพิ่มเพลงไม่สำเร็จ:', err);
        }
    }

    function renderAsmFilterChips(categories) {
        const row = document.getElementById('asmFilterRow');
        row.innerHTML = `<span class="asm-chip active" data-cat="all">ทั้งหมด</span>`;
        categories.forEach(cat => {
            const chip = document.createElement('span');
            chip.className = 'asm-chip';
            chip.dataset.cat = cat;
            chip.innerText = cat;
            row.appendChild(chip);
        });
        row.querySelectorAll('.asm-chip').forEach(chip => {
            chip.addEventListener('click', () => {
                row.querySelectorAll('.asm-chip').forEach(c => c.classList.remove('active'));
                chip.classList.add('active');
                asmActiveCat = chip.dataset.cat;
                renderAsmSongList();
            });
        });
    }

    function renderAsmSongList() {
        const keyword = document.getElementById('asmSearchInput').value.trim().toLowerCase();
        const list = document.getElementById('asmSongList');

        const filtered = asmAllSongs.filter(song => {
            const matchCat = asmActiveCat === 'all' || song.cat === asmActiveCat;
            const matchKeyword = !keyword || song.musicname.toLowerCase().includes(keyword);
            return matchCat && matchKeyword;
        });

        list.innerHTML = '';
        if (filtered.length === 0) {
            list.innerHTML = `<div class="asm-empty">ไม่พบเพลงที่ค้นหา</div>`;
            return;
        }

        filtered.forEach(song => {
            const imgUrl = song.image ? `/image/${song.image}` : '';
            const isExisting = asmExistingIds.includes(song.id);
            const row = document.createElement('div');
            row.className = 'asm-song-row';

            row.innerHTML = `
                <div class="asm-song-thumb" style="background-image:url('${imgUrl}')"></div>
                <div class="asm-song-info">
                    <div class="asm-song-name">${song.musicname}</div>
                    <div class="asm-song-sub">${song.cat}</div>
                </div>
                ${isExisting
                    ? `<span class="asm-song-added-tag">อยู่แล้ว</span>`
                    : `<input type="checkbox" data-id="${song.id}" ${asmSelectedIds.has(song.id) ? 'checked' : ''}>`
                }
            `;

            if (!isExisting) {
                row.querySelector('input[type="checkbox"]').addEventListener('change', (e) => {
                    if (e.target.checked) asmSelectedIds.add(song.id);
                    else asmSelectedIds.delete(song.id);
                    updateAsmSelectedCount();
                });
            }

            list.appendChild(row);
        });
    }

    function updateAsmSelectedCount() {
        document.getElementById('asmSelectedCount').innerText = `เลือกเพิ่มแล้ว ${asmSelectedIds.size} เพลง`;
        document.getElementById('asmAddBtn').disabled = asmSelectedIds.size === 0;
    }

    document.getElementById('asmSearchInput').addEventListener('input', renderAsmSongList);

    document.getElementById('asmAddBtn').addEventListener('click', async () => {
        if (asmSelectedIds.size === 0) return;
        try {
            const res = await fetch(`/add_songs_to_playlist/${asmPlaylistId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ song_ids: Array.from(asmSelectedIds) })
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);

            closeAddSongModal();
            // ถ้าเปิดมาจาก popup แก้ไขเพลย์ลิสต์ ให้โหลดข้อมูลในนั้นใหม่
            if (typeof openEditPlaylistModal === 'function') {
                openEditPlaylistModal(asmPlaylistId);
            } else {
                window.location.reload();
            }
        } catch (err) {
            console.error('เพิ่มเพลงไม่สำเร็จ:', err);
            alert('เพิ่มเพลงไม่สำเร็จ ลองใหม่อีกครั้ง');
        }
    });

    function closeAddSongModal() {
        document.getElementById('addSongModal').classList.remove('show');
    }

    document.getElementById('addSongModal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('show');
    });
</script>