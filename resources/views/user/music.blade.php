@extends('user/layouts')

@section('content')

<div class="page-wrapper">
    <div class="profile-content">
        <div class="hero-card" id="heroCard">
            <div class="hero-img" id="heroImg"></div>
            <div class="hero-tint"></div>
            <div class="hero-body">
                <div class="hero-top">
                    <div class="hero-chip" id="typetext"></div>
                    <div class="hero-nav">
                        <button id="heroPrev"><i class="fa-solid fa-chevron-left"></i></button>
                        <button id="heroNext"><i class="fa-solid fa-chevron-right"></i></button>
                    </div>
                </div>
                <div class="hero-bottom">
                    <div class="hero-info">
                        <h2 id="heroName"></h2>
                        <p id="heroSub"></p>
                        <div class="hero-dots" id="heroDots"></div>
                    </div>
                    <button class="hero-play" id="heroPlay"><i class="fa-solid fa-play"></i></button>
                </div>
            </div>
        </div>
         
        <div id="npPill" class="np-pill hidden">
            <div class="np-dot"></div>
            <div id="npThumb" class="np-thumb"></div>
            <div class="np-info">
                <div id="npName" class="np-name"></div>
                <div class="np-sub">กำลังเล่นอยู่</div>
            </div>
            <button class="np-btn" id="npBtn"><i class="fa-solid fa-pause"></i></button>
        </div>
 
        <div>
            <div class="sec-lbl"><i class="fa-solid fa-music"></i> เพลงในหมวด Focus</div>
            <div class="card-grid" id="cardGrid"></div>
        </div>
 
        <div class="foot">
            <button class="fbtn pri" id="randomBtn"><i class="fa-solid fa-shuffle"></i>สุ่มเพลง</button>
            <button class="fbtn sec" id="assessBtn"><i class="fa-solid fa-heart-pulse"></i>เลือกเป้าหมายการใช้งาน</button>
        </div>
    </div>
</div>

@endsection
