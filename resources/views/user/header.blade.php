<header class="header-container">
    <div class="dropdown">
        <div>
            <button class="menu-btn">
                <i class="fa-solid fa-bars"></i>
            </button>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('musicpage','focus') }}" onclick="filterMusic('focus')">Focus</a>
            <a href="{{ route('musicpage','mood') }}" onclick="filterMusic('mood')">Mood</a>
            <a href="{{ route('musicpage','relax') }}" onclick="filterMusic('relax')">Relax</a>
            <a href="{{ route('musicpage','sleep') }}" onclick="filterMusic('sleep')">Sleep</a>
            <a href="{{ route('musicpage','stress') }}" onclick="filterMusic('stress')">Stress</a>
        </nav>
    </div>

    <a href="{{ route('homepage') }}">
        <div class="logo"><img src="{{ asset('image/favicon.ico') }}"></div>
    </a>

    @if(empty(auth()->id()))
        <div class="auth-buttons">
            <a class="btnn btn-outline" href="{{route('regisfrom')}}">sign in</a>
            <a class="btn btn-outline" href="{{route('login')}}">login</a>
        </div>
   @else
<div class="user-menu">
    <button class="user-icon-btn" onclick="toggleUserMenu()">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </button>

    <div class="user-dropdown" id="userDropdown">
        <div class="user-dropdown-header">
            <div class="user-avatar-sm">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>

        <button onclick="openProfileModal()" class="dropdown-item">โปรไฟล์ของฉัน</button>
        <button onclick="openPasswordModal()" class="dropdown-item">เปลี่ยนรหัสผ่าน</button>
        <a href="{{ route('historypage') }}" class="dropdown-item">ประวัติการฟัง</a>
        <a href="{{ route('playlistpage') }}" class="dropdown-item">เพลย์ลิสต์ของฉัน</a>
        <a href="{{ route('wavepage') }}" class="dropdown-item">สร้างคลืนเสียง</a>
        

        <div class="dropdown-divider"></div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item dropdown-item-danger">ออกจากระบบ</button>
        </form>
    </div>
</div>
@endif
</header>


@push('scripts')

<script>
  function toggleUserMenu() {
    document.getElementById('userDropdown').classList.toggle('show');
    }
    document.addEventListener('click', function(e) {
        const menu = document.querySelector('.user-menu');
        if (menu && !menu.contains(e.target)) {
            document.getElementById('userDropdown').classList.remove('show');
        }
    });

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
@endpush




