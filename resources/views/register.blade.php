<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>สมัครสมาชิก</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #FBEAF0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 1.5rem;
        }
        .card {
            width: 100%;
            max-width: 500px;
            background-color: #FDF4F7;
            border: 1px solid #F4C0D1;
            border-radius: 24px;
            padding: 2.25rem 2rem;
        }
        .stepper {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 2.25rem;
        }
        .dot {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: #F4C0D1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #993556;
            transition: 0.3s;
            flex-shrink: 0;
        }
        .dot.active { background: #D4537E; color: #FBEAF0; }
        .line { width: 60px; height: 3px; background: #F4C0D1; margin: 0 10px; border-radius: 2px; }
        .line.active { background: #D4537E; }

        .step { display: none; animation: fadeIn 0.35s ease; }
        .step.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }

        h4 { text-align: center; color: #4B1528; margin: 0 0 4px; font-size: 1.15rem; }
        .step-sub { text-align: center; color: #993556; font-size: 0.85rem; margin: 0 0 1.5rem; }

        .row-2 { display: flex; gap: 12px; }
        .field { margin-bottom: 1rem; flex: 1; min-width: 0; }
        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #993556;
            margin-bottom: 0.4rem;
        }
        input, select {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1px solid #ED93B1;
            border-radius: 12px;
            font-size: 0.9rem;
            background: #FFFFFF;
            outline: none;
            font-family: inherit;
            min-width: 0;

            /* แก้ไขปัญหา iOS Safari */
            -webkit-appearance: none;
            appearance: none;
            height: 42px;
            box-sizing: border-box;
        }
        input[type="date"] {
            position: relative;
            -webkit-appearance: none;
            line-height: 1.2;
        }

        select {
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23993556' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1rem;
            padding-right: 2rem;
        }

        input:focus, select:focus {
            border-color: #D4537E;
            box-shadow: 0 0 0 3px rgba(212, 83, 126, 0.15);
        }

        .check-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            color: #993556;
            font-size: 0.85rem;
            margin-top: 4px;
        }
        .check-wrap input[type="checkbox"] {
            width: auto;
            height: auto;
            padding: 0;
            margin: 0;
            flex: none;
            -webkit-appearance: checkbox;
            appearance: auto;
            accent-color: #D4537E;
        }
        .check-wrap label {
            margin: 0;
            font-weight: 400;
            white-space: nowrap;
            cursor: pointer;
        }

        .btn-row { display: flex; gap: 10px; margin-top: 2rem; }
        button {
            flex: 1;
            padding: 0.7rem;
            border: none;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:disabled { opacity: .6; cursor: not-allowed; }
        .btn-pink { background-color: #D4537E; color: #FBEAF0; }
        .btn-pink:hover:not(:disabled) { background-color: #993556; }
        .btn-outline {
            background-color: #FFFFFF;
            border: 1px solid #ED93B1;
            color: #993556;
        }
        .btn-outline:hover { background-color: #FDEBF1; }

        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1rem;
            border-top: 1px solid #F4C0D1;
            font-size: 0.85rem;
            color: #993556;
        }
        .footer-text a { font-weight: 600; color: #72243E; text-decoration: none; }

        @media (max-width: 576px) {
            .card { padding: 1.75rem 1.25rem; }
            .row-2 { flex-direction: column; gap: 0; }
            .dot { width: 28px; height: 28px; font-size: 0.85rem; }
            .line { width: 40px; margin: 0 6px; }
        }

        /* ---------- error ใต้ช่อง ---------- */
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

        /* ---------- alert banner (อยู่ในการ์ด) ---------- */
        .pf-alert {
            display: none;
            text-align: left;
            background: #FBD9D9;
            border: 1px solid #EE9A9A;
            border-radius: 10px;
            padding: 12px 14px;
            margin: 0 0 1.25rem;
            color: #B03030;
        }
        .pf-alert.show { display: block; }

        .pf-alert-body { display: flex; align-items: flex-start; gap: 10px; }
        .pf-alert-icon { width: 20px; height: 20px; flex: none; margin-top: 1px; }
        .pf-alert-title { margin: 0; font-size: 13.5px; font-weight: 600; }
        .pf-alert-text  { margin: 2px 0 0; font-size: 12.5px; }

        .pf-alert-actions { display: flex; gap: 8px; margin-top: 10px; }
        .pf-alert-btn {
            flex: none;
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

        /* ---------- toast ---------- */
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
            flex: none;
            width: auto;
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
            animation: pf-toast-shrink 3s linear forwards; /* ให้ตรงกับ timer 3000ms */
        }
        @keyframes pf-toast-shrink {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }
        @media (prefers-reduced-motion: reduce) {
            .pf-toast { transition: none; }
        }
    </style>
</head>
<body>
    <div class="pf-toast" id="pf-toast" role="status" aria-live="polite">
        <div class="pf-toast-body">
            <svg class="pf-toast-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/>
            </svg>
            <div class="pf-toast-text">
                <p class="pf-toast-title" id="pf-toast-title">สมัครสมาชิกสำเร็จ</p>
                <p class="pf-toast-desc" id="pf-toast-desc">กำลังพาไปหน้าเข้าสู่ระบบ...</p>
            </div>
            <button type="button" class="pf-toast-close" id="pf-toast-close" aria-label="ปิด">&times;</button>
        </div>
        <div class="pf-toast-bar"></div>
    </div>

<div class="card">
    <div class="stepper">
        <div class="dot active" id="dot-1">1</div>
        <div class="line" id="line-1"></div>
        <div class="dot" id="dot-2">2</div>
    </div>

    <!-- alert อยู่ในการ์ด (เดิมอยู่นอกการ์ด ทำให้เลย์เอาต์เพี้ยน) -->
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

    <form action="{{ route('register') }}" method="POST" id="mainForm" novalidate>
        @csrf

        <div class="step active" id="step-1">
            <h4>ข้อมูลส่วนตัว</h4>
            <p class="step-sub">ระบุข้อมูลพื้นฐานของคุณ</p>

            <div class="row-2">
                <div class="field pf-field">
                    <label for="name">ชื่อ</label>
                    <input type="text" id="name" name="name" placeholder="ชื่อ" required>
                    <p class="pf-error" data-error-for="name" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span></span>
                    </p>
                </div>
                <div class="field pf-field">
                    <label for="surname">นามสกุล</label>
                    <input type="text" id="surname" name="surname" placeholder="นามสกุล" required>
                    <p class="pf-error" data-error-for="surname" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span></span>
                    </p>
                </div>
            </div>

            <div class="row-2">
                <div class="field pf-field">
                    <label for="birthdate">วันเกิด</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                    <p class="pf-error" data-error-for="birthdate" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span></span>
                    </p>
                </div>
                <div class="field pf-field">
                    <label for="gender">เพศ</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected hidden>เลือกเพศ</option>
                        <option value="ชาย">ชาย</option>
                        <option value="หญืง">หญิง</option>
                        <option value="ไม่ระบุ">ไม่ระบุ</option>
                    </select>
                    <p class="pf-error" data-error-for="gender" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span></span>
                    </p>
                </div>
            </div>

            <div class="btn-row">
                <button type="button" class="btn-pink" onclick="navStep(2)">ถัดไป</button>
            </div>
        </div>

        <div class="step" id="step-2">
            <h4>บัญชีผู้ใช้</h4>
            <p class="step-sub">กำหนดอีเมลและรหัสผ่าน</p>

            <div class="field pf-field">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com" required>
                <p class="pf-error" data-error-for="email" role="alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <span></span>
                </p>
            </div>

            <div class="row-2">
                <div class="field pf-field">
                    <label for="pw1">รหัสผ่าน</label>
                    <input type="password" id="pw1" name="password" placeholder="อย่างน้อย 8 ตัวอักษร" minlength="8" required>
                    <!-- Laravel ส่ง error ของ password_confirmation มาใต้คีย์ password -->
                    <p class="pf-error" data-error-for="password" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span></span>
                    </p>
                </div>
                <div class="field pf-field">
                    <label for="pw2">ยืนยันรหัสผ่าน</label>
                    <input type="password" id="pw2" name="password_confirmation" placeholder="ยืนยันอีกครั้ง" required>
                    <p class="pf-error" data-error-for="password_confirmation" role="alert">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        <span></span>
                    </p>
                </div>
            </div>

            <span class="check-wrap">
                <input type="checkbox" id="checkShow" onclick="toggleView()">
                <label for="checkShow">แสดงรหัสผ่าน</label>
            </span>

            <div class="btn-row">
                <button type="button" class="btn-outline" onclick="navStep(1)">ย้อนกลับ</button>
                <button type="submit" class="btn-pink" id="submitBtn"><span class="btn-text">ยืนยันการสมัคร</span></button>
            </div>
        </div>
    </form>

    <p class="footer-text">เป็นสมาชิกอยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
</div>

<script>
    /* ---------- สลับ step ---------- */
    function goStep(step) {
        const two = step === 2;
        document.getElementById('step-1').classList.toggle('active', !two);
        document.getElementById('step-2').classList.toggle('active', two);
        document.getElementById('dot-2').classList.toggle('active', two);
        document.getElementById('line-1').classList.toggle('active', two);
    }

    function navStep(step) {
        if (step === 2) {
            // form มี novalidate จึงตรวจเองทีละช่องของ step 1
            const fields = document.querySelectorAll('#step-1 input, #step-1 select');
            for (const f of fields) {
                if (!f.checkValidity()) { f.reportValidity(); return; }
            }
        }
        goStep(step);
    }

    function toggleView() {
        const t1 = document.getElementById('pw1');
        const t2 = document.getElementById('pw2');
        const type = t1.type === 'password' ? 'text' : 'password';
        t1.type = type;
        t2.type = type;
    }
</script>

<script>
    (function () {
        const form      = document.getElementById('mainForm');
        const alertBox  = document.getElementById('pf-alert');
        const alertText = document.getElementById('pf-alert-text');
        const retryBtn  = document.getElementById('pf-alert-retry');
        const toast     = document.getElementById('pf-toast');
        const submitBtn = document.getElementById('submitBtn');
        const btnText   = submitBtn.querySelector('.btn-text');
        const LOGIN_URL = @json(route('login'));

        let toastTimer;
        let redirecting = false;

        /* ---------- toast ---------- */
        function hideToast() {
            clearTimeout(toastTimer);
            toast.classList.remove('show');
        }

        function showToast(title, desc) {
            if (title) document.getElementById('pf-toast-title').textContent = title;
            if (desc)  document.getElementById('pf-toast-desc').textContent = desc;

            toast.classList.remove('show');
            void toast.offsetWidth; // รีสตาร์ตแอนิเมชันแถบเวลา
            toast.classList.add('show');

            clearTimeout(toastTimer);
            toastTimer = setTimeout(hideToast, 5000);
        }

        document.getElementById('pf-toast-close').addEventListener('click', hideToast);

        /* ---------- alert banner ---------- */
        function showBanner(text, canRetry = true) {
            alertText.textContent = text;
            retryBtn.style.display = canRetry ? '' : 'none';
            alertBox.classList.add('show');
        }

        function clearErrors() {
            alertBox.classList.remove('show');
            form.querySelectorAll('.pf-error').forEach(p => p.classList.remove('show'));
            form.querySelectorAll('.pf-field.has-error').forEach(f => f.classList.remove('has-error'));
        }

        retryBtn.addEventListener('click', () => {
            alertBox.classList.remove('show');
            form.requestSubmit();
        });
        document.getElementById('pf-alert-close').addEventListener('click', () => {
            alertBox.classList.remove('show');
        });

        /* ---------- error รายช่อง ---------- */
        function showFieldErrors(errors) {
            const orphan = [];
            let firstInput = null;

            Object.entries(errors).forEach(([field, messages]) => {
                const p = form.querySelector('[data-error-for="' + field + '"]');
                if (!p) { orphan.push(messages[0]); return; }

                p.querySelector('span').textContent = messages[0];
                p.classList.add('show');

                const wrap = p.closest('.pf-field');
                if (wrap) {
                    wrap.classList.add('has-error');
                    if (!firstInput) firstInput = wrap.querySelector('input, select');
                }
            });

            // error ของช่องที่ไม่มีที่แสดงใต้ช่อง ให้ขึ้นใน banner (ไม่ต้องมีปุ่มลองอีกครั้ง)
            if (orphan.length) showBanner(orphan.join(' / '), false);

            // ถ้า error อยู่ใน step ที่ไม่ได้เปิดอยู่ ให้สลับไป step นั้นก่อน
            if (firstInput) {
                goStep(firstInput.closest('#step-1') ? 1 : 2);
                firstInput.focus();
            }
        }

        /* พิมพ์/เลือกใหม่ → error ของช่องนั้นหายทันที */
        function clearFieldError(e) {
            const field = e.target.closest('.pf-field');
            if (!field || !field.classList.contains('has-error')) return;
            field.classList.remove('has-error');
            field.querySelector('.pf-error')?.classList.remove('show');
        }
        form.addEventListener('input', clearFieldError);
        form.addEventListener('change', clearFieldError);

        /* กด Enter ใน step 1 ให้ไป step 2 แทนที่จะ submit ทั้งฟอร์ม */
        form.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' && document.getElementById('step-1').classList.contains('active')
                && e.target.tagName !== 'BUTTON') {
                e.preventDefault();
                navStep(2);
            }
        });

        /* ---------- submit ---------- */
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // ตรวจ step 2 เอง (เพราะใช้ novalidate)
            const step2Fields = form.querySelectorAll('#step-2 input[required]');
            for (const f of step2Fields) {
                if (!f.checkValidity()) { f.reportValidity(); return; }
            }
            if (document.getElementById('pw1').value !== document.getElementById('pw2').value) {
                showFieldErrors({ password_confirmation: ['รหัสผ่านไม่ตรงกัน'] });
                return;
            }

            const formData = new FormData(form);
            const payload  = Object.fromEntries(formData.entries());

            submitBtn.disabled = true;
            btnText.textContent = 'กำลังสมัคร...';
            clearErrors();

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
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

                redirecting = true;
                showToast('สมัครสมาชิกสำเร็จ', 'กำลังพาไปหน้าเข้าสู่ระบบ...');
                setTimeout(() => {
                    window.location.href = result.redirect || LOGIN_URL;
                }, 4000);

            } catch (err) {
                if (err && err.status === 422 && err.data && err.data.errors) {
                    showFieldErrors(err.data.errors);
                } else if (err && err.status === 419) {
                    showBanner('หน้านี้หมดอายุ รีเฟรชหน้าแล้วลองอีกครั้ง', false);
                } else if (err && err.status) {
                    showBanner('เซิร์ฟเวอร์ขัดข้อง ข้อมูลที่กรอกยังอยู่ครบ');
                } else {
                    showBanner('เชื่อมต่อเซิร์ฟเวอร์ไม่ได้ ข้อมูลที่กรอกยังอยู่ครบ');
                }
                console.error(err);

            } finally {
                if (!redirecting) {
                    submitBtn.disabled = false;
                    btnText.textContent = 'ยืนยันการสมัคร';
                }
            }
        });
    })();
</script>
</body>
</html>