<div id="sidebar-backdrop" class="fixed inset-0 bg-slate-900/40 z-40 hidden lg:hidden transition-opacity"></div>

<aside id="sidebar-menu" class="w-64 h-screen fixed lg:sticky top-0 left-0 bg-white border-r border-slate-200 flex flex-col shrink-0 z-50 -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out">
    <div class="p-6 flex items-center justify-between lg:justify-start gap-3">
        <div class="flex items-center gap-3">
            <div class="bg-indigo-600 p-1.5 rounded-lg">
                <i class="bi bi-shield-lock-fill text-white text-xl"></i>
            </div>
            <span class="text-xl font-bold tracking-tight text-slate-900">Admin<span class="text-indigo-600">Controll</span></span>
        </div>
        <button id="mobile-menu-close" class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100">
            <i class="bi bi-x-lg text-xl"></i>
        </button>
    </div>
    
    <nav class="flex-1 px-4 space-y-1 overflow-y-auto [&::-webkit-scrollbar]:w-[2px] [&::-webkit-scrollbar-thumb]:bg-slate-300 [&::-webkit-scrollbar-track]:bg-transparent">
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider px-2 mb-2">Main Menu</p>
        <a href="{{ route('admin.home') }}" class="sidebar-item flex items-center {{ request()->is('*admin') ? 'active':'' }} gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('user.def') }}" class="sidebar-item {{ request()->is('admin/user*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-person-fill"></i> จัดการผู้ใช้งาน
        </a>
        <a href="{{ route('group.def') }}" class="sidebar-item {{ request()->is('admin/group*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-people-fill"></i> จัดการกลุ่มผู้ใช้งาน
        </a>
        <a href="{{ route('app.def') }}" class="sidebar-item {{ request()->is('admin/app*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-window"></i> จัดการแอปพลิเคชัน
        </a>
        <a href="{{ route('music.def') }}" class="sidebar-item {{ request()->is('admin/music*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-file-earmark-music"></i> จัดการคลื่นเสียง
        </a>
        <a href="{{ route('filter.def' )}}" class="sidebar-item {{ request()->is('admin/filter*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-funnel"></i> ตัวกรองคลื่นเสียง
        </a>
        <a href="{{ route('assessment.def' )}}" class="sidebar-item {{ request()->is('admin/assessment*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-file-earmark-text"></i> ประวัติการประเมินอารมณ์
        </a>
        <a href="{{ route('playlist.def' )}}" class="sidebar-item {{ request()->is('admin/playlist*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-music-note-list"></i> playlist
        </a>
        <a href="{{ route('history.def') }}" class="sidebar-item {{ request()->is('admin/history*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-hourglass-split"></i> ประวัติการฟังคลื่นเสียง
        </a>
        <a href="{{ route('log.def') }}" class="sidebar-item {{ request()->is('admin/log*') ? 'active':'' }} flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all text-slate-600">
            <i class="bi bi-clock-history"></i> ประวัติการใช้งานระบบ
        </a>
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="inline">
        @csrf
        <div class="p-4 border-t border-slate-100">
            <button type="submit" class="flex items-center gap-3 w-full px-3 py-2 rounded-lg text-rose-600 hover:bg-rose-50 transition-all">
                <i class="bi bi-box-arrow-left"></i> ออกจากระบบ
            </button>
        </div>
    </form>
</aside>
<script>
        document.addEventListener('DOMContentLoaded', function () {
            // ดึงปุ่มแฮมเบอร์เกอร์มาจากไฟล์ Header
            const toggleBtn = document.getElementById('mobile-menu-toggle');
            // ดึงปุ่มกากบาทมาจากไฟล์ Sidebar
            const closeBtn = document.getElementById('mobile-menu-close');
            // ดึงตัว Sidebar และ Backdrop
            const sidebar = document.getElementById('sidebar-menu');
            const backdrop = document.getElementById('sidebar-backdrop');

            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            }

            // ผูก Event ถ้ามี Element นั้นๆ อยู่ในหน้าจอ
            if(toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if(closeBtn) closeBtn.addEventListener('click', toggleSidebar);
            if(backdrop) backdrop.addEventListener('click', toggleSidebar);
        });
    </script>