<div class="pf-overlay" id="passwordModal">
    <div class="pf-card pf-card-sm">
        <span class="pf-close" onclick="closePasswordModal()">&times;</span>

        <h2 class="pf-pw-title">เปลี่ยนรหัสผ่าน</h2>
        <p class="pf-pw-subtitle">กรอกรหัสผ่านเดิมและรหัสผ่านใหม่</p>

        <!-- เพิ่มกล่องแสดงแจ้งเตือน (Alert) -->
        <div id="pf-alert" style="display: none; margin-bottom: 15px; font-size: 14px;"></div>

        <form id="password-form" action="{{ route('password.change') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="pf-field">
                <label for="pw-old">รหัสผ่านเดิม</label>
                <input type="password" id="pw-old" name="old_password" placeholder="••••••••" required>
            </div>
            <div class="pf-field">
                <label for="pw-new">รหัสผ่านใหม่</label>
                <input type="password" id="pw-new" name="new_password" placeholder="••••••••" required>
            </div>
            <div class="pf-field pf-field-last">
                <label for="pw-confirm">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" id="pw-confirm" name="new_password_confirmation" placeholder="••••••••" required>
            </div>

            <div class="pf-btn-row">
                <button type="button" class="pf-btn-cancel" onclick="closePasswordModal()">ยกเลิก</button>
                <!-- เพิ่มคลาส .btn-text เข้าไปครอบข้อความ -->
                <button type="submit" class="pf-btn-confirm"><span class="btn-text">ยืนยัน</span></button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('password-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form = e.target;
    const alertBox = document.getElementById('pf-alert');
    // เปลี่ยนจาก .pf-btn-save เป็น .pf-btn-confirm ให้ตรงกับ HTML
    const submitBtn = form.querySelector('.pf-btn-confirm');

    const formData = new FormData(form);
    const payload = Object.fromEntries(formData.entries());

    submitBtn.disabled = true;
    submitBtn.querySelector('.btn-text').textContent = 'กำลังบันทึก...';
    alertBox.style.display = 'none';

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || formData.get('_token'),
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (!response.ok) {
            throw { status: response.status, data: result };
        }

        alertBox.style.display = 'block';
        alertBox.style.color = 'green';
        alertBox.textContent = result.message || 'บันทึกข้อมูลสำเร็จ';

    } catch (err) {
        alertBox.style.display = 'block';
        alertBox.style.color = 'red';

        if (err.status === 422) {
            const errors = err.data.errors;
            const messages = Object.values(errors).flat().join('<br>');
            alertBox.innerHTML = messages;
        } else {
            alertBox.textContent = 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง';
        }
        console.error(err);

    } finally {
        submitBtn.disabled = false;
        submitBtn.querySelector('.btn-text').textContent = 'ยืนยัน'; // เปลี่ยนข้อความกลับตอนทำงานเสร็จ
    }
});
</script>