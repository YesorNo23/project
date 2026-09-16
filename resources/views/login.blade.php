<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}">
    <title>เข้าสู่ระบบ</title>
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
            max-width: 400px;
            background-color: #FDF4F7;
            border: 1px solid #F4C0D1;
            border-radius: 24px;
            padding: 2rem 1.75rem;
        }
        .icon-circle {
    width: 120px;  /* 📌 ปรับให้ใหญ่ขึ้น (จากเดิม 64px) */
    height: 120px; /* 📌 ปรับให้ใหญ่ขึ้น (จากเดิม 64px) */
    border-radius: 50%;
    background-color: #F4C0D1; /* ลบบรรทัดนี้ออกได้ถ้าไม่อยากให้เห็นสีพื้นหลัง */
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.icon-circle img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
        h3 {
            text-align: center;
            color: #4B1528;
            margin: 0 0 1.75rem;
            font-size: 1.25rem;
        }
        label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #993556;
            margin-bottom: 0.4rem;
        }
        .field { margin-bottom: 1rem; }
        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 0.6rem 0.9rem;
            border: 1px solid #ED93B1;
            border-radius: 12px;
            font-size: 0.95rem;
            background: #FFFFFF;
            outline: none;
        }
        input:focus {
            border-color: #D4537E;
            box-shadow: 0 0 0 3px rgba(212, 83, 126, 0.15);
        }
        .row-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            font-size: 0.85rem;
        }
        .check-wrap { display: flex; align-items: center; gap: 6px; color: #993556; }
        a { color: #993556; text-decoration: none; }
        a:hover { color: #72243E; }
        button {
            width: 100%;
            padding: 0.7rem;
            border: none;
            border-radius: 999px;
            background-color: #D4537E;
            color: #FBEAF0;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background-color: #993556; }
        .footer-text {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: #993556;
        }
        .footer-text a { font-weight: 600; color: #72243E; }
        
    </style>
</head>
<body>

<div class="card">
    <div class="icon-circle">
        <img src="{{ asset('image/logo.png') }}">
    </div>
    <h3>คลื่นเสียงบำบัด</h3>

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="field">
            <label for="email">อีเมลผู้ใช้งาน</label>
            <input type="email" id="email" name="email" placeholder="example@mail.com" required>
        </div>

        <div class="field">
            <label for="password">รหัสผ่าน</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div class="row-between">
            <span class="check-wrap">
                <input type="checkbox" id="showPassword" onclick="togglePassword()">
                <label for="showPassword" style="margin:0; font-weight:400;">แสดงรหัสผ่าน</label>
            </span>
        </div>

        <button type="submit">เข้าสู่ระบบ</button>
    </form>

    <p class="footer-text">ยังไม่มีบัญชี? <a href="{{ route('regisfrom') }}">สร้างบัญชีใหม่</a></p>
</div>

<script>
    function togglePassword() {
        const pw = document.getElementById('password');
        pw.type = pw.type === 'password' ? 'text' : 'password';
    }
</script>
</body>
</html>