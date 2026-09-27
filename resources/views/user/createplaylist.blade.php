<!--
    ===================================================================
    Create Playlist — Modal Popup
    ใช้สไตล์เดียวกับ Edit Playlist Modal (class ขึ้นต้น epm-)
    ถ้าหน้านี้โหลดคู่กับ edit_playlist_modal.php อยู่แล้ว ไม่ต้องแปะ <style> ซ้ำ
    (ลบ <style> block ด้านล่างออกได้เลยถ้า CSS epm- มีอยู่แล้วในหน้า)

    เปิดด้วย openCreatePlaylistModal()
    ยังไม่มีส่วนเพิ่มเพลง — สร้างแค่ชื่อ + คำอธิบายก่อน เพิ่มเพลงทีหลังผ่าน
    popup "เพิ่มเพลง" (openAddSongModal) ได้หลังสร้างเสร็จ
    ===================================================================
-->

<div class="epm-overlay" id="createPlaylistModal">
    <div class="epm-card">
        <span class="epm-close" onclick="closeCreatePlaylistModal()">&times;</span>

        <h2 class="epm-title">สร้างเพลย์ลิสต์ใหม่</h2>

        <form id="createPlaylistForm">
            <div class="epm-field">
                <label for="cpmName">ชื่อเพลย์ลิสต์</label>
                <input type="text" id="cpmName" name="name" placeholder="เช่น ก่อนนอนคืนนี้" required>
            </div>

            <div class="epm-field">
                <label for="cpmDesc">คำอธิบาย (ถ้ามี)</label>
                <textarea id="cpmDesc" name="description" rows="2" placeholder="บอกเล่าเกี่ยวกับเพลย์ลิสต์นี้..."></textarea>
            </div>

            <button type="submit" class="epm-btn-save">สร้างเพลย์ลิสต์</button>
        </form>
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
    }
    .epm-btn-save:hover { background: #993556; }
</style>

<script>
   function openCreatePlaylistModal() {
    document.getElementById('createPlaylistForm').reset();
    document.getElementById('createPlaylistModal').classList.add('show');
    }

    function closeCreatePlaylistModal() {
        document.getElementById('createPlaylistModal').classList.remove('show');
    }

    document.getElementById('createPlaylistForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        try {
            // แก้ไข URL เป็น /update_playlist (ตัด / ท้ายออก)
            const res = await fetch('/update_playlist', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json', // บังคับให้ตอบกลับเป็น JSON เสมอ
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                },
                body: JSON.stringify({
                    name: document.getElementById('cpmName').value,
                    description: document.getElementById('cpmDesc').value
                })
            });

            const data = await res.json();

            // ตรวจสอบสถานะ Response
            if (!res.ok) {
                // ดึงข้อความแจ้งเตือนจาก Laravel มาแสดง (เช่น กรอกข้อมูลไม่ครบ)
                const errorMsg = data.errors 
                    ? Object.values(data.errors).flat().join('\n') 
                    : (data.message || `HTTP ${res.status}`);
                throw new Error(errorMsg);
            }

            closeCreatePlaylistModal();
            window.location.reload();

        } catch (err) {
            console.error('สร้างเพลย์ลิสต์ไม่สำเร็จ:', err);
            alert('สร้างเพลย์ลิสต์ไม่สำเร็จ:\n' + err.message);
        }
    });

    document.getElementById('createPlaylistModal').addEventListener('click', function (e) {
        if (e.target === this) this.classList.remove('show');
    });
</script>