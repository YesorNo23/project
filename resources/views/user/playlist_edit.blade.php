<!--
    ===================================================================
    Edit Playlist — Modal Popup
    ไม่พึ่ง CDN ภายนอก (ยกเว้น Font Awesome ที่เว็บคุณโหลดอยู่แล้ว)
    เปิดด้วย openEditPlaylistModal(playlistId)
    ===================================================================
-->

<div class="epm-overlay" id="editPlaylistModal">
    <div class="epm-card">
        <span class="epm-close" onclick="closeEditPlaylistModal()">&times;</span>

        <h2 class="epm-title">แก้ไขเพลย์ลิสต์</h2>

        <!-- Cover preview (auto จากเพลงแรก) -->
        <div class="epm-cover-wrap">
            <div class="epm-cover" id="epmCover"></div>
            <span class="epm-cover-hint">ปกใช้รูปจากเพลงแรกอัตโนมัติ</span>
        </div>

        <form id="editPlaylistForm">
            <input type="hidden" id="epmPlaylistId" name="playlist_id">

            <div class="epm-field">
                <label for="epmName">ชื่อเพลย์ลิสต์</label>
                <input type="text" id="epmName" name="name" required>
            </div>

            <div class="epm-field">
                <label for="epmDesc">คำอธิบาย</label>
                <textarea id="epmDesc" name="description" rows="2"></textarea>
            </div>

            <!-- Song management -->
            <div class="epm-field">
                <label id="epmSongCountLabel">เพลงในเพลย์ลิสต์</label>
                <div class="epm-song-list" id="epmSongList">
                    <!-- รายการเพลงจะถูกสร้างที่นี่โดย JS -->
                </div>
                <!-- แก้ไขจุดนี้: ลบ inline onclick ออก ใช้ JS EventListener ด้านล่างจัดการอย่างเดียว -->
                <div class="epm-add-song-link" id="epmAddSongLink">+ เพิ่มเพลงเข้าเพลย์ลิสต์</div>
            </div>

            <button type="submit" class="epm-btn-save" id="epmSaveBtn">บันทึกการเปลี่ยนแปลง</button>
        </form>
        <button type="button" class="epm-btn-delete" id="epmDeleteBtn">ลบเพลย์ลิสต์</button>
    </div>
</div>

<!-- Confirm delete popup ซ้อนอีกชั้น (ป้องกันกดลบพลาด) -->
<div class="epm-overlay" id="confirmDeletePlaylistModal">
    <div class="epm-card epm-card-sm">
        <h3 class="epm-confirm-title">ลบเพลย์ลิสต์นี้?</h3>
        <p class="epm-confirm-text">การลบเพลย์ลิสต์ไม่สามารถย้อนกลับได้ เพลงในเพลย์ลิสต์จะไม่ถูกลบ แต่เพลย์ลิสต์นี้จะหายไปถาวร</p>
        <div class="epm-btn-row">
            <button type="button" class="epm-btn-cancel" onclick="closeConfirmDeleteModal()">ยกเลิก</button>
            <button type="button" class="epm-btn-confirm-delete" id="epmConfirmDeleteBtn">ลบเลย</button>
        </div>
    </div>
</div>

<style>
    .epm-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(43, 32, 51, 0.5);
        z-index: 100000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .epm-overlay.show { display: flex; }

    .epm-card {
        width: 100%;
        max-width: 400px;
        background: #fff;
        border-radius: 18px;
        padding: 26px 24px 20px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        position: relative;
        max-height: 85vh;
        overflow-y: auto;
        box-sizing: border-box;
    }
    .epm-card-sm { max-width: 320px; text-align: center; }

    .epm-close {
        position: absolute;
        top: 16px;
        right: 18px;
        font-size: 22px;
        color: #b0a8bd;
        cursor: pointer;
        line-height: 1;
    }
    .epm-close:hover { color: #5c5266; }

    .epm-title { margin: 0 0 20px; font-size: 19px; font-weight: 700; color: #2b2033; text-align: center; }

    .epm-cover-wrap { display: flex; flex-direction: column; align-items: center; margin-bottom: 20px; }
    .epm-cover {
        width: 96px;
        height: 96px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 8px 18px rgba(0,0,0,0.15);
        background-size: cover;
        background-position: center;
        background-color: #eee;
    }
    .epm-cover-hint { font-size: 10.5px; color: #b0a8bd; margin-top: 6px; }

    .epm-field { margin-bottom: 16px; }
    .epm-field label {
        font-size: 12.5px;
        font-weight: 600;
        color: #5c5266;
        display: block;
        margin-bottom: 6px;
    }
    .epm-field input[type="text"],
    .epm-field textarea {
        width: 100%;
        padding: 9px 12px;
        border: 1px solid #e5e0ea;
        border-radius: 10px;
        font-size: 13.5px;
        box-sizing: border-box;
        font-family: inherit;
        outline: none;
    }
    .epm-field textarea { resize: none; }
    .epm-field input:focus,
    .epm-field textarea:focus {
        border-color: #D4537E;
        box-shadow: 0 0 0 3px rgba(212, 83, 126, 0.12);
    }

    .epm-song-list {
        max-height: 170px;
        overflow-y: auto;
        border: 1px solid #f0edf3;
        border-radius: 10px;
    }
    .epm-song-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-bottom: 1px solid #f5f2f8;
    }
    .epm-song-row:last-child { border-bottom: none; }
    .epm-song-thumb { width: 34px; height: 34px; border-radius: 6px; flex-shrink: 0; background-size: cover; background-position: center; }
    .epm-song-name { flex: 1; font-size: 12.5px; color: #2b2033; min-width: 0; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .epm-song-remove { color: #c9b3c2; font-size: 15px; cursor: pointer; flex-shrink: 0; }
    .epm-song-remove:hover { color: #C4325A; }

    .epm-add-song-link {
        margin-top: 8px;
        font-size: 12px;
        color: #D4537E;
        font-weight: 600;
        cursor: pointer;
    }
    .epm-add-song-link:hover { color: #993556; }

    .epm-btn-save {
        width: 100%;
        padding: 11px;
        border: none;
        border-radius: 999px;
        background: #D4537E;
        color: #fff;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        margin-bottom: 10px;
    }
    .epm-btn-save:hover { background: #993556; }

    .epm-btn-delete {
        width: 100%;
        padding: 10px;
        border: 1px solid #f3d5df;
        border-radius: 999px;
        background: #fff;
        color: #C4325A;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .epm-btn-delete:hover { background: #fdf2f5; }

    .epm-confirm-title { margin: 0 0 8px; font-size: 17px; color: #2b2033; }
    .epm-confirm-text { margin: 0 0 20px; font-size: 12.5px; color: #8a7f99; line-height: 1.5; }
    .epm-btn-row { display: flex; gap: 10px; }
    .epm-btn-cancel {
        flex: 1;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #e5e0ea;
        color: #5c5266;
        font-size: 13px;
        font-weight: 600;
        padding: 10px;
        cursor: pointer;
    }
    .epm-btn-cancel:hover { background: #f8f7fa; }
    .epm-btn-confirm-delete {
        flex: 1;
        border-radius: 999px;
        background: #C4325A;
        color: #fff;
        border: none;
        font-size: 13px;
        font-weight: 600;
        padding: 10px;
        cursor: pointer;
    }
    .epm-btn-confirm-delete:hover { background: #9c2748; }
</style>

<script>
    let epmCurrentSongs = [];

    // เปิด popup แก้ไข พร้อมโหลดข้อมูลเพลย์ลิสต์นั้นมาเติมในฟอร์ม
    async function openEditPlaylistModal(playlistId) {
        if (!playlistId) {
            alert('ไม่พบรหัสเพลย์ลิสต์');
            return;
        }

        document.getElementById('editPlaylistModal').classList.add('show');
        document.getElementById('epmPlaylistId').value = playlistId;

        try {
            const res = await fetch(`/get_playlist/${playlistId}`, {
                headers: { 
                    "Accept": "application/json", 
                    "X-Requested-With": "XMLHttpRequest" 
                }
            });
            if (!res.ok) throw new Error(`HTTP ${res.status}`);
            const data = await res.json();

            epmCurrentSongs = data.songs || [];

            const coverImg = data.cover ? `/image/${data.cover}` : (epmCurrentSongs[0] ? `/image/${epmCurrentSongs[0].image}` : '');
            document.getElementById('epmCover').style.backgroundImage = coverImg ? `url('${coverImg}')` : 'none';
            document.getElementById('epmName').value = data.name || '';
            document.getElementById('epmDesc').value = data.description || '';
            document.getElementById('epmSongCountLabel').innerText = `เพลงในเพลย์ลิสต์ (${epmCurrentSongs.length})`;

            renderEpmSongList();
        } catch (err) {
            console.error('โหลดข้อมูลเพลย์ลิสต์ไม่สำเร็จ:', err);
            alert('โหลดข้อมูลเพลย์ลิสต์ไม่สำเร็จ');
        }
    }

    function renderEpmSongList() {
        const list = document.getElementById('epmSongList');
        list.innerHTML = '';
        epmCurrentSongs.forEach((song) => {
            const imgUrl = song.image ? `/image/${song.image}` : '';
            const row = document.createElement('div');
            row.className = 'epm-song-row';
            row.innerHTML = `
                <div class="epm-song-thumb" style="background-image:url('${imgUrl}')"></div>
                <span class="epm-song-name">${song.musicname}</span>
                <span class="epm-song-remove" data-id="${song.id}">✕</span>
            `;
            row.querySelector('.epm-song-remove').addEventListener('click', () => {
                epmCurrentSongs = epmCurrentSongs.filter(s => s.id !== song.id);
                document.getElementById('epmSongCountLabel').innerText = `เพลงในเพลย์ลิสต์ (${epmCurrentSongs.length})`;
                renderEpmSongList();
            });
            list.appendChild(row);
        });
    }

    function closeEditPlaylistModal() {
        document.getElementById('editPlaylistModal').classList.remove('show');
    }

    // Submit แก้ไขเพลย์ลิสต์
    document.getElementById('editPlaylistForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const saveBtn = document.getElementById('epmSaveBtn');
        const playlistId = document.getElementById('epmPlaylistId').value;

        if (!playlistId) {
            alert('ไม่พบรหัสเพลย์ลิสต์');
            return;
        }

        saveBtn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch(`/update_playlist/${playlistId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    name: document.getElementById('epmName').value,
                    description: document.getElementById('epmDesc').value,
                    song_ids: epmCurrentSongs.map(s => s.id)
                })
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message || `HTTP ${res.status}`);

            closeEditPlaylistModal();
            window.location.reload(); 
        } catch (err) {
            console.error('บันทึกไม่สำเร็จ:', err);
            alert(err.message || 'บันทึกไม่สำเร็จ ลองใหม่อีกครั้ง');
        } finally {
            saveBtn.disabled = false;
        }
    });

    // คลิกเพื่อเปิด modal เพิ่มเพลง
    document.getElementById('epmAddSongLink').addEventListener('click', () => {
        const playlistId = document.getElementById('epmPlaylistId').value;
        if (playlistId) {
            openAddSongModal(playlistId);
        } else {
            alert('กรุณารอโหลดข้อมูลเพลย์ลิสต์สักครู่...');
        }
    });

    // --- Delete flow ---
    document.getElementById('epmDeleteBtn').addEventListener('click', () => {
        const playlistId = document.getElementById('epmPlaylistId').value;
        if (!playlistId) {
            alert('ไม่พบรหัสเพลย์ลิสต์');
            return;
        }
        document.getElementById('confirmDeletePlaylistModal').classList.add('show');
    });

    function closeConfirmDeleteModal() {
        document.getElementById('confirmDeletePlaylistModal').classList.remove('show');
    }

    document.getElementById('epmConfirmDeleteBtn').addEventListener('click', async (e) => {
        const confirmBtn = e.currentTarget;
        const playlistId = document.getElementById('epmPlaylistId').value;

        if (!playlistId) {
            alert('ไม่พบรหัสเพลย์ลิสต์');
            return;
        }

        confirmBtn.disabled = true;

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch(`/delete_playlist/${playlistId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message || `HTTP ${res.status}`);

            closeConfirmDeleteModal();
            window.location.href = '/playlist';
        } catch (err) {
            console.error('ลบไม่สำเร็จ:', err);
            alert(err.message || 'ลบไม่สำเร็จ ลองใหม่อีกครั้ง');
        } finally {
            confirmBtn.disabled = false;
        }
    });

    // ปิด modal เมื่อคลิกพื้นหลังนอกการ์ด
    document.querySelectorAll('.epm-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('show');
        });
    });
</script>