<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NexusCore')</title>
    <link rel="shortcut icon" href="C:\Users\User\Downloads\ดีไซน์ที่ยังไม่ได้ตั้งชื่อ.png" type="image/x-icon">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="icon" type="image/x-icon" href="{{ asset('image/favicon.ico') }}">

    @if(session('permission_error'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: 'ข้อผิดพลาดเกี่ยวกับสิทธิ์!',
            html: `
                <div class="text-slate-600">{{ session('permission_error') }}</div>
                <div class="mt-2 text-rose-500 font-semibold text-sm">
                    (กรุณาติดต่อผู้ดูแลระบบหากคุณต้องการสิทธิ์เพิ่มเติม)
                </div>
            `,
            icon: 'error', // เปลี่ยนจาก warning เป็น error ให้ดูชัดเจนว่าถูกปฏิเสธ
            confirmButtonColor: '#4f46e5',
            confirmButtonText: 'รับทราบ',
            allowOutsideClick: false
        });
    });
</script>
@endif

    <style>
        body { font-family: 'Kanit', sans-serif; background-color: #f8fafc; }
        .sidebar-item:hover { background-color: #f1f5f9; color: #4f46e5; }
        .sidebar-item.active { background-color: #eff6ff; color: #2563eb; border-right: 4px solid #2563eb; }
        /* ... styles เดิมของคุณ ... */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.9rem;
            color: #334155;
            transition: all 0.3s ease;
        }
        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: #64748b;
            margin-bottom: 0.4rem;
        }
        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.4rem 1rem;
            margin-bottom: 1rem;
            outline: none;
        }
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            padding: 0.2rem 1.5rem 0.2rem 0.5rem;
        }
        table.dataTable font-family: 'Kanit', sans-serif !important; border-bottom: none !important; }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #4f46e5 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem;
        }
    </style>
    @stack('styles')
</head>
<body class="flex bg-slate-50">

    @include('admin/sidebar')

    <div class="flex-1 flex flex-col min-w-0">
        @include('admin/header')

        <main class="p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
</body>
</html>