@extends('user/layouts')

@section('content')

<div class="page-wrapper">
    <div class="myplaylist-content">

        <div class="playlist-header">
            <div>
                <div class="category-title">เพลย์ลิสต์ของฉัน</div>
                <p class="playlist-subcount" id="playlistCount"></p>
            </div>
            <div>
                <button class="pf-btn-confirm"onclick="openCreatePlaylistModal()"><i class="fa-solid fa-plus"></i> เพลย์ลิสต์</button>
            </div>
            
        </div>

        <!-- การ์ดเพลย์ลิสต์จะถูกสร้างที่นี่โดย JS -->
        <div class="playlist-vinyl-grid" id="playlistGrid"></div>

        <div class="playlist-scroll-hint">
           
        </div>

    </div>
</div>

@endsection
@push('styles')
<style>
.myplaylist-content {
    flex: 1; /* ให้ยืดเต็มพื้นที่ที่เหลือ */
    padding: 40px 40px; /* ปรับให้เท่ากับ padding ที่ตั้งไว้ใน hero-body */
    width: 100%;
    box-sizing: border-box;
    background: #f9cadf;

    /* --- เพิ่มคำสั่งจัดตรงกลางด้านล่างนี้ --- */
    display: flex;
    flex-direction: column;  /* เรียงเนื้อหาลงมาเป็นแนวตั้ง */
    
    align-items: center;     /* จัดให้อยู่ตรงกลางแนวนอน */
    text-align: center;      /* ให้ข้อความทั้งหมดอยู่ตรงกลาง */
}
</style>

@endpush