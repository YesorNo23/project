
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
        <div class="logo">LOGO</div>
    </a>
    @if(auth()->id())
    <div class="auth-buttons">
        <a class="btnn btn-outline" href="{{route('regisfrom')}}">sign in</a>
        <a class="btn btn-outline" href="{{route('login')}}">login</a>
    </div>
    @endif
</header>