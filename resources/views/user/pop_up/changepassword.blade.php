<div class="pf-overlay" id="passwordModal">
    <div class="pf-card pf-card-sm">
        <span class="pf-close" onclick="closePasswordModal()">&times;</span>

        <h2 class="pf-pw-title">เปลี่ยนรหัสผ่าน</h2>
        <p class="pf-pw-subtitle">กรอกรหัสผ่านเดิมและรหัสผ่านใหม่</p>

        <form id="password-form" action="{{ route('password.change') }}" method="POST" novalidate>
            @csrf
            @method('PUT')

            <div class="pf-field">
                <label for="pw-old">รหัสผ่านเดิม</label>
                <input type="password" id="pw-old" name="old_password" placeholder="••••••••" autocomplete="current-password" required>
                <p class="pf-error" data-error-for="old_password" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span></span>
                </p>
            </div>

            <div class="pf-field">
                <label for="pw-new">รหัสผ่านใหม่</label>
                <input type="password" id="pw-new" name="new_password" placeholder="••••••••" autocomplete="new-password" required>
                <p class="pf-error" data-error-for="new_password" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span></span>
                </p>
            </div>

            <div class="pf-field pf-field-last">
                <label for="pw-confirm">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" id="pw-confirm" name="new_password_confirmation" placeholder="••••••••" autocomplete="new-password" required>
                <p class="pf-error" data-error-for="new_password_confirmation" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span></span>
                </p>
            </div>

            <!-- banner ส่งข้อมูลไม่สำเร็จ -->
            <div class="pf-alert" id="pw-alert" role="alert">
                <div class="pf-alert-body">
                    <svg class="pf-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 20h.01"/><path d="M8.5 16.429a5 5 0 0 1 7 0"/><path d="M5 12.859a10 10 0 0 1 5.17-2.69"/><path d="M19 12.859a10 10 0 0 0-2.007-1.523"/><path d="M2 8.82a15 15 0 0 1 4.177-2.643"/><path d="M22 8.82a15 15 0 0 0-11.288-3.764"/><path d="m2 2 20 20"/>
                    </svg>
                    <div>
                        <p class="pf-alert-title">ส่งข้อมูลไม่สำเร็จ</p>
                        <p class="pf-alert-text" id="pw-alert-text"></p>
                        <div class="pf-alert-actions">
                            <button type="button" class="pf-alert-btn" id="pw-alert-retry">ลองอีกครั้ง</button>
                            <button type="button" class="pf-alert-btn ghost" id="pw-alert-close">ปิด</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pf-btn-row">
                <button type="button" class="pf-btn-cancel" onclick="closePasswordModal()">ยกเลิก</button>
                <button type="submit" class="pf-btn-confirm"><span class="btn-text">ยืนยัน</span></button>
            </div>
        </form>
    </div>
</div>


<script>
(function () {
    const form = document.getElementById('password-form');
    const alertBox = document.getElementById('pw-alert');
    const alertText = document.getElementById('pw-alert-text');
    const retryBtn = document.getElementById('pw-alert-retry');

    function clearErrors() {
        alertBox.classList.remove('show');
        form.querySelectorAll('.pf-error').forEach(p => p.classList.remove('show'));
        form.querySelectorAll('.pf-field.has-error').forEach(f => f.classList.remove('has-error'));
    }

    function showBanner(text, canRetry = true) {
        alertText.textContent = text;
        retryBtn.style.display = canRetry ? '' : 'none';
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

        if (orphan.length) showBanner(orphan.join(' / '), false);

        form.querySelector('.has-error input')?.focus();
    }

    // พิมพ์แก้ช่องไหน error ของช่องนั้นหายทันที
    form.addEventListener('input', function (e) {
        const field = e.target.closest('.pf-field');
        if (!field || !field.classList.contains('has-error')) return;
        field.classList.remove('has-error');
        field.querySelector('.pf-error')?.classList.remove('show');
    });

    retryBtn.addEventListener('click', () => form.requestSubmit());
    document.getElementById('pw-alert-close').addEventListener('click', () => {
        alertBox.classList.remove('show');
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const submitBtn = form.querySelector('.pf-btn-confirm');
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

            // สำเร็จ: ล้างรหัสผ่านออกจากฟอร์ม, ปิด modal, แสดง toast
            form.reset();
            closePasswordModal();
            window.showToast?.(
                'เปลี่ยนรหัสผ่านแล้ว',
                result.message || 'ใช้รหัสผ่านใหม่ในการเข้าสู่ระบบครั้งถัดไป'
            );

        } catch (err) {
            if (err.status === 422 && err.data && err.data.errors) {
                showFieldErrors(err.data.errors);
            } else if (err.status === 419) {
                showBanner('หน้านี้หมดอายุ รีเฟรชหน้าแล้วลองอีกครั้ง', false);
            } else if (err.status) {
                showBanner('เซิร์ฟเวอร์ขัดข้อง ข้อมูลที่กรอกยังอยู่ครบ');
            } else {
                showBanner('เชื่อมต่อเซิร์ฟเวอร์ไม่ได้ ข้อมูลที่กรอกยังอยู่ครบ');
            }
            console.error(err);

        } finally {
            submitBtn.disabled = false;
            submitBtn.querySelector('.btn-text').textContent = 'ยืนยัน';
        }
    });
})();
</script>