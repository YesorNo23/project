@extends('user/layouts')

@section('content')

    <main class="main-content">

        <!-- Slider ใหญ่ด้านบน -->
        <div class="slider-wrapper">
            <button id="prevSlideBtn" class="arrow-btn">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <div class="slider-container">
                <div id="mainSliderTrack" class="slider-track"></div>
            </div>
            <button id="nextSlideBtn" class="arrow-btn">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>

            <!-- ส่วนแสดงเพลงแยกตามหมวดหมู่ -->
            <div id="categorySections" style="width:100%;max-width:100%;margin-top:30px;"></div>

            <!-- ปุ่มด้านล่าง -->
            <div class="footer-buttons">
                <button class="btn btn-round" id="randomBtn">สุ่ม</button>
                <button class="btn btn-round" id="assessBtn">ประเมินอารมณ์</button>
            </div>
        
    </main>

    

@endsection
