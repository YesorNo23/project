<!-- ============ PROFILE MODAL ============ -->
<div class="pf-overlay" id="profileModal">
    <div class="pf-card">
        <span class="pf-close" onclick="closeProfileModal()">&times;</span>
 
        <div class="pf-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
        </div>
 
        <h2 class="pf-name">{{ (auth()->user()->name ?? '') . ' ' . (auth()->user()->surname ?? '') }}</h2>
        <p class="pf-subtitle">สมาชิก &middot; คลื่นเสียงบำบัด</p>
 
        <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
    @csrf
    @method('PUT')

    <div class="pf-section">
        <div class="pf-field">
            <label for="pf-name">ชื่อ</label>
            <input type="text" id="pf-name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
        </div>

        <div class="pf-field">
            <label for="pf-surname">นามสกุล</label>
            <input type="text" id="pf-surname" name="surname" value="{{ auth()->user()->surname ?? '' }}" required>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="pf-birthdate">วันเกิด</label>
                <input type="date" id="pf-birthdate" name="birthdate" value="{{ auth()->user()->birthdate ?? '' }}" required>
            </div>
            <div class="pf-field">
                <label for="pf-gender">เพศ</label>
                <select id="pf-gender" name="gender" required>
                    <option value="male" {{ (auth()->user()->gender ?? '') == 'male' ? 'selected' : '' }}>ชาย</option>
                    <option value="female" {{ (auth()->user()->gender ?? '') == 'female' ? 'selected' : '' }}>หญิง</option>
                    <option value="other" {{ (auth()->user()->gender ?? '') == 'other' ? 'selected' : '' }}>อื่นๆ</option>
                </select>
            </div>
        </div>

        <div class="pf-field pf-field-last">
            <label>อีเมล</label>
            <div class="pf-readonly">{{ auth()->user()->email ?? '' }}</div>
        </div>
    </div>

    <!-- ไว้แสดง error / success -->
    <div id="pf-alert" style="display:none; margin-bottom:10px;"></div>

    <button type="submit" class="pf-btn-save">
        <span class="btn-text">บันทึกการเปลี่ยนแปลง</span>
    </button>
</form>
    </div>
</div>
<style>
    .pf-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(74, 16, 48, 0.55);
        z-index: 100000;
        align-items: center;
        justify-content: center;
        padding: 1.5rem;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .pf-overlay.show { display: flex; }
 
    .pf-card {
        width: 100%;
        max-width: 360px;
        background: #FDF4F7;
        border-radius: 22px;
        padding: 1.75rem 1.5rem 1.5rem;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0,0,0,0.35);
        position: relative;
        max-height: 90vh;
        overflow-y: auto;
        box-sizing: border-box;
    }
    .pf-card-sm { max-width: 320px; text-align: left; }
 
    .pf-close {
        position: absolute;
        top: 14px;
        right: 16px;
        font-size: 20px;
        color: #993556;
        cursor: pointer;
        line-height: 1;
    }
    .pf-close:hover { color: #72243E; }
 
    .pf-avatar {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: #F4C0D1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 600;
        color: #72243E;
        margin: 0 auto 0.75rem;
    }
    .pf-name { margin: 0 0 2px; font-size: 17px; color: #4B1528; }
    .pf-subtitle { margin: 0 0 1rem; font-size: 12.5px; color: #993556; }
 
    .pf-section {
        border-top: 1px solid #F4C0D1;
        padding-top: 14px;
        text-align: left;
    }
    .pf-field { margin-bottom: 12px; }
    .pf-field-last { margin-bottom: 4px; }
    .pf-row { display: flex; gap: 10px; }
    .pf-row .pf-field { flex: 1; }
 
    .pf-card label {
        font-size: 11px;
        font-weight: 600;
        color: #993556;
        display: block;
        margin-bottom: 4px;
    }
    .pf-card input[type="text"],
    .pf-card input[type="date"],
    .pf-card input[type="password"],
    .pf-card select {
        width: 100%;
        padding: 7px 10px;
        border: 1px solid #ED93B1;
        border-radius: 10px;
        font-size: 13px;
        box-sizing: border-box;
        background: #fff;
        font-family: inherit;
        outline: none;
    }
    .pf-card input:focus,
    .pf-card select:focus {
        border-color: #D4537E;
        box-shadow: 0 0 0 3px rgba(212, 83, 126, 0.15);
    }
 
    .pf-readonly {
        padding: 7px 10px;
        border: 1px solid #F4C0D1;
        border-radius: 10px;
        font-size: 13px;
        background: #F8ECF0;
        color: #8A7580;
    }
 
    .pf-btn-save {
        width: 100%;
        border-radius: 999px;
        background: #D4537E;
        color: #fff;
        border: none;
        padding: 11px;
        font-size: 13.5px;
        font-weight: 600;
        margin-top: 16px;
        cursor: pointer;
    }
    .pf-btn-save:hover { background: #993556; }
 
    .pf-pw-title { margin: 0 0 4px; font-size: 16px; color: #4B1528; text-align: center; }
    .pf-pw-subtitle { margin: 0 0 1.25rem; font-size: 12px; color: #993556; text-align: center; }
 
    .pf-btn-row { display: flex; gap: 10px; margin-top: 6px; }
    .pf-btn-cancel {
        flex: 1;
        border-radius: 999px;
        background: #fff;
        border: 1px solid #ED93B1;
        color: #993556;
        font-size: 13px;
        font-weight: 600;
        padding: 10px;
        cursor: pointer;
    }
    .pf-btn-cancel:hover { background: #FDEBF1; }
    .pf-btn-confirm {
        flex: 1;
        border-radius: 999px;
        background: #D4537E;
        color: #fff;
        border: none;
        font-size: 13px;
        font-weight: 600;
        padding: 10px;
        cursor: pointer;
    }
    .pf-btn-confirm:hover { background: #993556; }
</style>
<script>
    function openProfileModal() {
        document.getElementById('profileModal').classList.add('show');
    }
    function closeProfileModal() {
        document.getElementById('profileModal').classList.remove('show');
    }
    function openPasswordModal() {
        document.getElementById('passwordModal').classList.add('show');
    }
    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('show');
    }
 
    // ปิด modal เมื่อคลิกพื้นหลังนอกการ์ด
    document.querySelectorAll('.pf-overlay').forEach(function (overlay) {
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('show');
        });
    });
</script>
<script>
document.getElementById('profile-form').addEventListener('submit', async function (e) {
    e.preventDefault(); // 🛑 หยุดไม่ให้ form โหลดหน้าใหม่แบบปกติ

    const form = e.target;
    const alertBox = document.getElementById('pf-alert');
    const submitBtn = form.querySelector('.pf-btn-save');

    // เก็บข้อมูลจากฟอร์มทั้งหมด (รวม _token, _method อัตโนมัติ)
    const formData = new FormData(form);

    // แปลง FormData เป็น object ธรรมดา เพื่อส่งเป็น JSON
    const payload = Object.fromEntries(formData.entries());

    // ปิดปุ่มกันกดซ้ำ + แสดงสถานะกำลังโหลด
    submitBtn.disabled = true;
    submitBtn.querySelector('.btn-text').textContent = 'กำลังบันทึก...';
    alertBox.style.display = 'none';

    try {
        const response = await fetch(form.action, {
            method: 'POST', // ใช้ POST จริง แต่แนบ _method=PUT ผ่าน @method('PUT') ให้ Laravel รู้ว่าเป็น PUT
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                    || formData.get('_token'),
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!response.ok) {
            // กรณี validation error (status 422) หรือ error อื่นๆ
            throw { status: response.status, data: result };
        }

        // ✅ สำเร็จ
        alertBox.style.display = 'block';
        alertBox.style.color = 'green';
        alertBox.textContent = result.message || 'บันทึกข้อมูลสำเร็จ';

        // ถ้าต้องการปิด popup อัตโนมัติหลังบันทึกสำเร็จ
        // closeProfilePopup(); 
        // หรือ reload ข้อมูลบางส่วนโดยไม่ reload ทั้งหน้า

    } catch (err) {
        alertBox.style.display = 'block';
        alertBox.style.color = 'red';

        if (err.status === 422) {
            // แสดง error รายฟิลด์จาก Laravel validation
            const errors = err.data.errors;
            const messages = Object.values(errors).flat().join('<br>');
            alertBox.innerHTML = messages;
        } else {
            alertBox.textContent = 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
        }
        console.error(err);

    } finally {
        submitBtn.disabled = false;
        submitBtn.querySelector('.btn-text').textContent = 'บันทึกการเปลี่ยนแปลง';
    }
});
</script>