<!-- ============ PROFILE MODAL ============ -->
        <div class="pf-toast" id="pf-toast" role="status" aria-live="polite">
            <div class="pf-toast-body">
                <svg class="pf-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>
                </svg>
                <div class="pf-toast-text">
                    <p class="pf-toast-title" id="pf-toast-title">บันทึกข้อมูลแล้ว</p>
                    <p class="pf-toast-desc" id="pf-toast-desc">โปรไฟล์ของคุณอัปเดตเรียบร้อย</p>
                </div>
                <button type="button" class="pf-toast-close" onclick="hideToast()" aria-label="ปิด">&times;</button>
            </div>
            <div class="pf-toast-bar"></div>
        </div>

        <div class="pf-alert" id="pf-alert" role="alert">
            <div class="pf-alert-body">
                <svg class="pf-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h.01"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/><path d="M5 12.859a10 10 0 0 1 5.17-2.69"/><path d="M19 12.859a10 10 0 0 0-2.007-1.523"/><path d="M2 8.82a15 15 0 0 1 4.177-2.643"/><path d="M22 8.82a15 15 0 0 0-11.288-3.764"/><path d="m2 2 20 20"/>
                </svg>
                <div>
                    <p class="pf-alert-title">ส่งข้อมูลไม่สำเร็จ</p>
                    <p class="pf-alert-text" id="pf-alert-text"></p>
                    <div class="pf-alert-actions">
                        <button type="button" class="pf-alert-btn" id="pf-alert-retry">ลองอีกครั้ง</button>
                        <button type="button" class="pf-alert-btn ghost" id="pf-alert-close">ปิด</button>
                    </div>
                </div>
            </div>
        </div>

<div class="pf-overlay" id="profileModal">
    <div class="pf-card">
    
        <span class="pf-close" onclick="closeProfileModal()">&times;</span>
 
        <div class="pf-avatar">
            {{ strtoupper(substr(auth()->user()->name ?? 'E', 0, 1)) }}
        </div>
 
        <h2 class="pf-name">{{ (auth()->user()->name ?? '') . ' ' . (auth()->user()->surname ?? '') }}</h2>
        <p class="pf-subtitle">สมาชิก &middot; คลื่นเสียงบำบัด</p>
 
        <form id="profile-form" action="{{ route('profile.update') }}" method="POST" novalidate>
    @csrf
    @method('PUT')

    <div class="pf-section">
        <div class="pf-field">
            <label for="pf-name">ชื่อ</label>
            <input type="text" id="pf-name" name="name" value="{{ auth()->user()->name ?? '' }}" required>
            <p class="pf-error" data-error-for="name" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span></span>
            </p>
        </div>

        <div class="pf-field">
            <label for="pf-surname">นามสกุล</label>
            <input type="text" id="pf-surname" name="surname" value="{{ auth()->user()->surname ?? '' }}" required>
            <p class="pf-error" data-error-for="surname" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                </svg>
                <span></span>
            </p>
        </div>

        <div class="pf-row">
            <div class="pf-field">
                <label for="pf-birthdate">วันเกิด</label>
                <input type="date" id="pf-birthdate" name="birthdate" value="{{ auth()->user()->birthdate ?? '' }}" required>
                <p class="pf-error" data-error-for="birthdate" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span></span>
                </p>
            </div>
            <div class="pf-field">
                <label for="pf-gender">เพศ</label>
                <select id="pf-gender" name="gender" required>
                    <option value="ชาย" {{ (auth()->user()->gender ?? '') == 'ชาย' ? 'selected' : '' }}>ชาย</option>
                    <option value="หญิง" {{ (auth()->user()->gender ?? '') == 'หญิง' ? 'selected' : '' }}>หญิง</option>
                    <option value="ไม่ระบุ" {{ (auth()->user()->gender ?? '') == 'ไม่ระบุ' ? 'selected' : '' }}>ไม่ระบุ</option>
                </select>
                <p class="pf-error" data-error-for="gender" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span></span>
                </p>
            </div>
        </div>
    </div>

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

    .pf-error {
    display: none;
    align-items: center;
    gap: 6px;
    margin: 6px 0 0;
    font-size: 12px;
    color: #C0392B;
    }
    .pf-error.show { display: flex; }
    .pf-error svg { width: 15px; height: 15px; flex: none; }

    .pf-field.has-error input,
    .pf-field.has-error select {
        border-color: #E57373;
        box-shadow: 0 0 0 3px rgba(224, 75, 74, 0.2);
    }

    .pf-alert {
    display: none;
    text-align: left;
    background: #FBD9D9;
    border: 1px solid #EE9A9A;
    border-radius: 10px;
    padding: 12px 14px;
    margin-top: 12px;
    color: #B03030;
    }
    .pf-alert.show { display: block; }

    .pf-alert-body { display: flex; align-items: flex-start; gap: 10px; }
    .pf-alert-icon { width: 20px; height: 20px; flex: none; margin-top: 1px; }
    .pf-alert-title { margin: 0; font-size: 13.5px; font-weight: 600; }
    .pf-alert-text  { margin: 2px 0 0; font-size: 12.5px; }

    .pf-alert-actions { display: flex; gap: 8px; margin-top: 10px; }
    .pf-alert-btn {
        font-family: inherit;
        font-size: 12.5px;
        font-weight: 500;
        color: #B03030;
        background: transparent;
        border: 1px solid #E57373;
        border-radius: 8px;
        padding: 5px 14px;
        cursor: pointer;
    }
    .pf-alert-btn:hover { background: rgba(224, 75, 74, 0.1); }
    .pf-alert-btn.ghost { border-color: transparent; }

    .pf-toast {
    position: fixed;
    top: 16px;
    left: 50%;
    z-index: 100001;
    width: 320px;
    max-width: calc(100vw - 32px);
    background: #D4EDD9;
    border: 1px solid #7BC58C;
    border-radius: 12px;
    color: #1E5B2E;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
    opacity: 0;
    transform: translate(-50%, -12px);
    pointer-events: none;
    transition: opacity .2s ease, transform .2s ease;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .pf-toast.show {
        opacity: 1;
        transform: translate(-50%, 0);
        pointer-events: auto;
    }
    .pf-toast-body {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        padding: 12px 14px 14px;
    }
    .pf-toast-icon { width: 22px; height: 22px; flex: none; }
    .pf-toast-text { flex: 1; text-align: left; }
    .pf-toast-title { margin: 0; font-size: 14px; font-weight: 600; }
    .pf-toast-desc { margin: 2px 0 0; font-size: 12.5px; }
    .pf-toast-close {
        background: none;
        border: none;
        padding: 0;
        color: inherit;
        font-size: 20px;
        line-height: 1;
        cursor: pointer;
    }
    .pf-toast-bar {
        height: 3px;
        background: #4CAF64;
        transform-origin: left;
    }
    .pf-toast.show .pf-toast-bar {
        animation: pf-toast-shrink 3s linear forwards;
    }
    @keyframes pf-toast-shrink {
        from { transform: scaleX(1); }
        to   { transform: scaleX(0); }
    }
    @media (prefers-reduced-motion: reduce) {
        .pf-toast { transition: none; }
    }
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
(function () {
    const form = document.getElementById('profile-form');
    const alertBox = document.getElementById('pf-alert');
    const alertText = document.getElementById('pf-alert-text');
    const retryBtn = document.getElementById('pf-alert-retry');

    let toastTimer;

    function showBanner(text, canRetry = true) {
        if (!alertBox) { alert(text); return; }
        alertText.textContent = text;
        retryBtn.style.display = canRetry ? '' : 'none';
        alertBox.classList.add('show');
    }

    retryBtn.addEventListener('click', () => form.requestSubmit());
    document.getElementById('pf-alert-close').addEventListener('click', () => {
        alertBox.classList.remove('show');
    });

    window.showToast = function (title, desc) {
        const toast = document.getElementById('pf-toast');
        if (title) document.getElementById('pf-toast-title').textContent = title;
        if (desc)  document.getElementById('pf-toast-desc').textContent = desc;

        // รีสตาร์ตแอนิเมชันแถบเวลาทุกครั้งที่แสดง
        toast.classList.remove('show');
        void toast.offsetWidth;
        toast.classList.add('show');

        clearTimeout(toastTimer);
        toastTimer = setTimeout(hideToast, 3000);
    }

    window.hideToast = function () {
        clearTimeout(toastTimer);
        document.getElementById('pf-toast').classList.remove('show');
    };

    function clearErrors() {
        alertBox.classList.remove('show');
        form.querySelectorAll('.pf-error').forEach(p => p.classList.remove('show'));
        form.querySelectorAll('.pf-field.has-error').forEach(f => f.classList.remove('has-error'));
    }

    function showBanner(text) {
        alertText.textContent = text;
        alertBox.classList.add('show');
    }

    function showFieldErrors(errors) {
        const orphan = [];

        Object.entries(errors).forEach(([field, messages]) => {
            const p = form.querySelector('[data-error-for="' + field + '"]');
            if (!p) { orphan.push(messages[0]); return; }

            p.querySelector('span').textContent = messages[0];
            p.classList.add('show');
            p.closest('.pf-field').classList.add('has-error');
        });

        // error ของช่องที่ไม่มีที่แสดงใต้ช่อง ให้ขึ้นใน banner
        if (orphan.length) showBanner(orphan.join(' / '));

        form.querySelector('.has-error input, .has-error select')?.focus();
    }

    // พิมพ์แก้ช่องไหน error ของช่องนั้นหายทันที
    form.addEventListener('input', function (e) {
        const field = e.target.closest('.pf-field');
        if (!field || !field.classList.contains('has-error')) return;
        field.classList.remove('has-error');
        field.querySelector('.pf-error')?.classList.remove('show');
    });

    form.addEventListener('change', function (e) {
        const field = e.target.closest('.pf-field');
        if (!field || !field.classList.contains('has-error')) return;
        field.classList.remove('has-error');
        field.querySelector('.pf-error')?.classList.remove('show');
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('.pf-btn-save');
        const formData = new FormData(form);
        const payload = Object.fromEntries(formData.entries());

        submitBtn.disabled = true;
        submitBtn.querySelector('.btn-text').textContent = 'กำลังบันทึก...';
        clearErrors();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                        || formData.get('_token'),
                },
                body: JSON.stringify(payload),
            });

            let result = {};
            try { result = await response.json(); } catch (_) {}

            if (!response.ok) {
                throw { status: response.status, data: result };
            }
            showToast('บันทึกข้อมูลแล้ว', 'โปรไฟล์ของคุณอัปเดตเรียบร้อย');

            // TODO: success (ยังไม่ทำตามที่ขอ)

        } catch (err) {
            if (err.status === 422 && err.data && err.data.errors) {
                showFieldErrors(err.data.errors);
            } else if (err.status === 419) {
                showBanner('หน้านี้หมดอายุ รีเฟรชหน้าแล้วลองอีกครั้ง');
            } else if (err.status) {
                showBanner('เซิร์ฟเวอร์ขัดข้อง ข้อมูลที่กรอกยังอยู่ครบ');
            } else {
                showBanner('เชื่อมต่อเซิร์ฟเวอร์ไม่ได้ ข้อมูลที่กรอกยังอยู่ครบ');
            }
            console.error(err);

        } finally {
            submitBtn.disabled = false;
            submitBtn.querySelector('.btn-text').textContent = 'บันทึกการเปลี่ยนแปลง';
        }
    });
})();
</script>