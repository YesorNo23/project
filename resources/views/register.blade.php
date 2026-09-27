<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            -webkit-appearance: none; /* ปิด UI ดั้งเดิมของ Safari */
            appearance: none;
            height: 42px; /* ล็อกความสูงให้เท่ากันทุกช่อง */
            box-sizing: border-box;
        }
        /* จัดระเบียบช่อง วันเกิด ให้แสดงผลถูกต้องใน Safari */
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
            padding: 0;
            margin: 0;
            flex: none;
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
        .btn-pink { background-color: #D4537E; color: #FBEAF0; }
        .btn-pink:hover { background-color: #993556; }
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

        /* Fix: stack paired fields on narrow screens so text/placeholders
           don't get clipped or overlap each other */
        @media (max-width: 576px) {
            .card { padding: 1.75rem 1.25rem; }
            .row-2 { flex-direction: column; gap: 0; }
            .dot { width: 28px; height: 28px; font-size: 0.85rem; }
            .line { width: 40px; margin: 0 6px; }
        }
    </style>
</head>
<body>

<div class="card">
    <div class="stepper">
        <div class="dot active" id="dot-1">1</div>
        <div class="line" id="line-1"></div>
        <div class="dot" id="dot-2">2</div>
    </div>

    <form action="{{route('register')}}" method="POST" id="mainForm">
        @csrf

        <div class="step active" id="step-1">
            <h4>ข้อมูลส่วนตัว</h4>
            <p class="step-sub">ระบุข้อมูลพื้นฐานของคุณ</p>

            <div class="row-2">
                <div class="field">
                    <label for="name">ชื่อ</label>
                    <input type="text" id="name" name="name" placeholder="ชื่อ" required>
                </div>
                <div class="field">
                    <label for="surname">นามสกุล</label>
                    <input type="text" id="surname" name="surname" placeholder="นามสกุล" required>
                </div>
            </div>

            <div class="row-2">
                <div class="field">
                    <label for="birthdate">วันเกิด</label>
                    <input type="date" id="birthdate" name="birthdate" required>
                </div>
                <div class="field">
                    <label for="gender">เพศ</label>
                    <select id="gender" name="gender" required>
                        <option value="" disabled selected hidden>เลือกเพศ</option>
                        <option value="male">ชาย</option>
                        <option value="female">หญิง</option>
                        <option value="other">อื่นๆ</option>
                    </select>
                </div>
            </div>

            <div class="btn-row">
                <button type="button" class="btn-pink" onclick="navStep(2)">ถัดไป</button>
            </div>
        </div>

        <div class="step" id="step-2">
            <h4>บัญชีผู้ใช้</h4>
            <p class="step-sub">กำหนดอีเมลและรหัสผ่าน</p>

            <div class="field">
                <label for="email">อีเมล</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com" required>
            </div>

            <div class="row-2">
                <div class="field">
                    <label for="pw1">รหัสผ่าน</label>
                    <input type="password" id="pw1" name="password" placeholder="อย่างน้อย 8 ตัวอักษร" required>
                </div>
                <div class="field">
                    <label for="pw2">ยืนยันรหัสผ่าน</label>
                    <input type="password" id="pw2" name="password_confirmation" placeholder="ยืนยันอีกครั้ง" required>
                </div>
            </div>

            <span class="check-wrap">
                <input type="checkbox" id="checkShow" onclick="toggleView()">
                <label for="checkShow" style="margin:0; font-weight:400;">แสดงรหัสผ่าน</label>
            </span>
            <input type="hidden" name="usertype" value="user">
            <input type="hidden" name="status" value="1">

            <div class="btn-row">
                <button type="button" class="btn-outline" onclick="navStep(1)">ย้อนกลับ</button>
                <button type="submit" class="btn-pink">ยืนยันการสมัคร</button>
            </div>
        </div>
    </form>

    <p class="footer-text">เป็นสมาชิกอยู่แล้ว? <a href="{{ route('login') }}">เข้าสู่ระบบ</a></p>
</div>

<script>
    function navStep(step) {
        if (step === 2) {
            const fields = document.querySelectorAll('#step-1 input, #step-1 select');
            let isOk = true;
            fields.forEach(f => { if (!f.checkValidity()) { f.reportValidity(); isOk = false; } });
            if (isOk) {
                document.getElementById('step-1').classList.remove('active');
                document.getElementById('step-2').classList.add('active');
                document.getElementById('dot-2').classList.add('active');
                document.getElementById('line-1').classList.add('active');
            }
        } else {
            document.getElementById('step-2').classList.remove('active');
            document.getElementById('step-1').classList.add('active');
            document.getElementById('dot-2').classList.remove('active');
            document.getElementById('line-1').classList.remove('active');
        }
    }

    function toggleView() {
        const t1 = document.getElementById('pw1');
        const t2 = document.getElementById('pw2');
        const isPass = t1.type === 'password';
        t1.type = isPass ? 'text' : 'password';
        t2.type = isPass ? 'text' : 'password';
    }
</script>
</body>
</html>